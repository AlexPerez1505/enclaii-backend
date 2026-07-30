<?php

namespace App\Services;

use App\Models\Reporte;
use Illuminate\Support\Str;

class ReportPdfGenerator
{
    private const PAGE_WIDTH = 612;
    private const PAGE_HEIGHT = 792;
    private const MARGIN = 54;

    /**
     * @return array{name: string, data: string}
     */
    public function make(Reporte $reporte): array
    {
        $reporte->loadMissing(['estudio.paciente', 'usuario']);

        return [
            'name' => $this->filename($reporte),
            'data' => $this->bytes($reporte),
        ];
    }

    public function bytes(Reporte $reporte): string
    {
        return $this->buildPdf($this->reportLines($reporte));
    }

    /**
     * @return array<int, array{text: string, font: string, size: int, leading: int, after: int}>
     */
    private function reportLines(Reporte $reporte): array
    {
        $estudio = $reporte->estudio;
        $paciente = $estudio?->paciente;
        $patientName = $paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'Paciente no registrado';
        $studyType = $estudio?->tipo ?? 'Estudio endoscopico';
        $studyFolio = $estudio?->folio ?? ('Estudio '.$estudio?->id);
        $doctor = $reporte->usuario?->name ?? $estudio?->medico ?? 'Medico no especificado';
        $studyDate = format_user_date($estudio?->fecha ?? $reporte->created_at) ?: 'Sin fecha';
        $reportDate = format_user_date($reporte->created_at) ?: 'Sin fecha';
        $content = $this->contentText($reporte);

        $lines = [
            $this->line('ENCLAII', 'F2', 11, 15, 2),
            $this->line('INFORME DE '.Str::upper($studyType), 'F2', 16, 20, 8),
            $this->line('Paciente: '.$patientName, 'F2', 11, 15, 0),
            $this->line('Folio del estudio: '.$studyFolio, 'F1', 10, 14, 0),
            $this->line('Tipo de estudio: '.$studyType, 'F1', 10, 14, 0),
            $this->line('Fecha del estudio: '.$studyDate, 'F1', 10, 14, 0),
            $this->line('Fecha del reporte: '.$reportDate, 'F1', 10, 14, 0),
            $this->line('Medico: '.$doctor, 'F1', 10, 14, 10),
            $this->line('Contenido del reporte', 'F2', 12, 16, 4),
        ];

        if ($content === '') {
            $lines[] = $this->line('Sin contenido registrado.', 'F1', 10, 14, 0);

            return $lines;
        }

        foreach ($this->wrapText($content, 92) as $textLine) {
            $lines[] = $this->line($textLine, 'F1', 10, 14, $textLine === '' ? 5 : 0);
        }

        return $lines;
    }

    /**
     * @return array{text: string, font: string, size: int, leading: int, after: int}
     */
    private function line(string $text, string $font, int $size, int $leading, int $after): array
    {
        return compact('text', 'font', 'size', 'leading', 'after');
    }

    /**
     * @return array<int, string>
     */
    private function wrapText(string $text, int $maxChars): array
    {
        $paragraphs = preg_split('/\R/u', $text) ?: [];
        $lines = [];

        foreach ($paragraphs as $paragraph) {
            $paragraph = trim(preg_replace('/[ \t]+/u', ' ', $paragraph) ?? $paragraph);

            if ($paragraph === '') {
                $lines[] = '';
                continue;
            }

            foreach (explode("\n", wordwrap($paragraph, $maxChars, "\n", true)) as $line) {
                $lines[] = $line;
            }
        }

        return $lines;
    }

    private function contentText(Reporte $reporte): string
    {
        if (filled($reporte->contenido_html)) {
            $html = (string) $reporte->contenido_html;
            $html = preg_replace('/<\s*br\s*\/?>/i', "\n", $html) ?? $html;
            $html = preg_replace('/<\s*li[^>]*>/i', "\n- ", $html) ?? $html;
            $html = preg_replace('/<\s*\/(p|div|li|h[1-6]|tr|section)[^>]*>/i', "\n", $html) ?? $html;

            return $this->normalizeText(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        }

        return $this->normalizeText((string) $reporte->contenido_texto);
    }

    private function normalizeText(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = preg_replace('/[^\P{C}\n\t]+/u', '', $text) ?? $text;
        $text = preg_replace("/\n{3,}/", "\n\n", $text) ?? $text;

        return trim($text);
    }

    private function filename(Reporte $reporte): string
    {
        $estudio = $reporte->estudio;
        $paciente = $estudio?->paciente;
        $patient = Str::slug($paciente?->nombre_completo ?? $estudio?->paciente_nombre ?? 'paciente') ?: 'paciente';
        $folio = Str::slug($estudio?->folio ?? 'estudio-'.$estudio?->id) ?: 'estudio';

        return 'reporte-'.$folio.'-'.$patient.'-'.$reporte->id.'.pdf';
    }

    /**
     * @param  array<int, array{text: string, font: string, size: int, leading: int, after: int}>  $lines
     */
    private function buildPdf(array $lines): string
    {
        $pages = [];
        $stream = '';
        $y = self::PAGE_HEIGHT - self::MARGIN;

        foreach ($lines as $line) {
            if ($y < self::MARGIN + $line['leading']) {
                $pages[] = $stream;
                $stream = '';
                $y = self::PAGE_HEIGHT - self::MARGIN;
            }

            if ($line['text'] !== '') {
                $stream .= $this->textCommand(self::MARGIN, $y, $line['font'], $line['size'], $line['text']);
            }

            $y -= $line['leading'] + $line['after'];
        }

        $pages[] = $stream;

        return $this->assemblePdf($pages);
    }

    private function textCommand(int $x, int $y, string $font, int $size, string $text): string
    {
        return "BT /{$font} {$size} Tf 1 0 0 1 {$x} {$y} Tm ".$this->pdfString($text)." Tj ET\n";
    }

    /**
     * @param  array<int, string>  $pages
     */
    private function assemblePdf(array $pages): string
    {
        $objects = [
            1 => '<< /Type /Catalog /Pages 2 0 R >>',
            3 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>',
            4 => '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>',
        ];
        $pageObjects = [];
        $nextObject = 5;

        foreach ($pages as $content) {
            $contentObject = $nextObject++;
            $pageObject = $nextObject++;

            $objects[$contentObject] = "<< /Length ".strlen($content)." >>\nstream\n{$content}endstream";
            $objects[$pageObject] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 '.self::PAGE_WIDTH.' '.self::PAGE_HEIGHT.'] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents '.$contentObject.' 0 R >>';
            $pageObjects[] = $pageObject.' 0 R';
        }

        $objects[2] = '<< /Type /Pages /Kids ['.implode(' ', $pageObjects).'] /Count '.count($pageObjects).' >>';
        ksort($objects);

        $pdf = "%PDF-1.4\n%".chr(226).chr(227).chr(207).chr(211)."\n";
        $offsets = [0 => 0];

        foreach ($objects as $objectNumber => $body) {
            $offsets[$objectNumber] = strlen($pdf);
            $pdf .= "{$objectNumber} 0 obj\n{$body}\nendobj\n";
        }

        $xrefOffset = strlen($pdf);
        $count = count($objects) + 1;
        $pdf .= "xref\n0 {$count}\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i < $count; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size {$count} /Root 1 0 R >>\nstartxref\n{$xrefOffset}\n%%EOF";

        return $pdf;
    }

    private function pdfString(string $text): string
    {
        $encoded = function_exists('iconv')
            ? @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text)
            : false;

        if ($encoded === false) {
            $encoded = preg_replace('/[^\x20-\x7E]/', '?', $text) ?? $text;
        }

        $escaped = '';
        for ($i = 0, $length = strlen($encoded); $i < $length; $i++) {
            $byte = ord($encoded[$i]);
            $char = $encoded[$i];

            if ($char === '\\' || $char === '(' || $char === ')') {
                $escaped .= '\\'.$char;
            } elseif ($byte < 32 || $byte > 126) {
                $escaped .= sprintf('\\%03o', $byte);
            } else {
                $escaped .= $char;
            }
        }

        return '('.$escaped.')';
    }
}
