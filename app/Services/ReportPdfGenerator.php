<?php

namespace App\Services;

use App\Models\Reporte;
use Illuminate\Support\Str;

class ReportPdfGenerator
{
    public function __construct(private readonly SimpleTextPdf $pdf) {}

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
        return $this->pdf->make($this->reportLines($reporte));
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

}
