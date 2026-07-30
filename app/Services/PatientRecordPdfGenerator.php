<?php

namespace App\Services;

use App\Models\Estudio;
use App\Models\Paciente;
use App\Models\Reporte;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PatientRecordPdfGenerator
{
    public function __construct(private readonly SimpleTextPdf $pdf) {}

    /**
     * @return array{name: string, data: string}
     */
    public function make(Paciente $paciente): array
    {
        $estudios = $paciente->estudios()
            ->with(['reportes.usuario'])
            ->withCount(['capturas', 'videos', 'reportes'])
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->get();

        return [
            'name' => $this->filename($paciente),
            'data' => $this->pdf->make($this->lines($paciente, $estudios)),
        ];
    }

    /**
     * @param  Collection<int, Estudio>  $estudios
     * @return array<int, array{text: string, font: string, size: int, leading: int, after: int}>
     */
    private function lines(Paciente $paciente, Collection $estudios): array
    {
        $lines = [
            $this->line('ENCLAII', 'F2', 11, 15, 2),
            $this->line('EXPEDIENTE DEL PACIENTE', 'F2', 16, 20, 8),
            $this->line('Paciente: '.($paciente->nombre_completo ?: 'Sin nombre'), 'F2', 11, 15, 0),
            $this->line('Folio: '.($paciente->folio ?: 'Sin folio'), 'F1', 10, 14, 0),
            $this->line('Edad: '.($paciente->edad ? $paciente->edad.' anos' : 'Sin edad'), 'F1', 10, 14, 0),
            $this->line('Sexo: '.($paciente->sexo ?: 'No especificado'), 'F1', 10, 14, 0),
            $this->line('Fecha de nacimiento: '.$this->dateText($paciente->fecha_nacimiento), 'F1', 10, 14, 0),
            $this->line('Telefono: '.($paciente->telefono ?: 'Sin telefono'), 'F1', 10, 14, 0),
            $this->line('Correo: '.($paciente->email ?: 'Sin correo'), 'F1', 10, 14, 0),
            $this->line('Medico: '.($paciente->medico ?: 'Sin medico'), 'F1', 10, 14, 0),
            $this->line('Procedimiento: '.($paciente->procedimiento ?: 'Sin procedimiento'), 'F1', 10, 14, 0),
            $this->line('Diagnostico preliminar: '.($paciente->diagnostico_preliminar ?: 'Sin diagnostico'), 'F1', 10, 14, 10),
            $this->line('Estudios registrados', 'F2', 12, 16, 4),
        ];

        if ($estudios->isEmpty()) {
            $lines[] = $this->line('Sin estudios registrados.', 'F1', 10, 14, 0);

            return $lines;
        }

        foreach ($estudios as $estudio) {
            $lines[] = $this->line(($estudio->tipo ?: 'Estudio').' - '.($estudio->folio ?: 'Sin folio'), 'F2', 11, 15, 2);
            $lines[] = $this->line('Fecha: '.$this->dateText($estudio->fecha).' | Estado: '.$estudio->estado_texto, 'F1', 10, 14, 0);
            $lines[] = $this->line('Medico: '.($estudio->medico ?: $paciente->medico ?: 'Sin medico'), 'F1', 10, 14, 0);
            $lines[] = $this->line('Diagnostico: '.($estudio->diagnostico ?: 'Sin diagnostico'), 'F1', 10, 14, 0);
            $lines[] = $this->line('Observaciones: '.($estudio->observaciones ?: 'Sin observaciones'), 'F1', 10, 14, 0);
            $lines[] = $this->line('Archivos: '.$estudio->capturas_count.' fotos, '.$estudio->videos_count.' videos, '.$estudio->reportes_count.' reportes', 'F1', 10, 14, 2);

            $reportes = $estudio->reportes->sortByDesc('created_at')->values();
            if ($reportes->isEmpty()) {
                $lines[] = $this->line('Reportes: sin reportes registrados.', 'F1', 10, 14, 6);
                continue;
            }

            foreach ($reportes as $reporte) {
                $lines[] = $this->line('Reporte #'.$reporte->id.' - '.$this->dateText($reporte->created_at), 'F2', 10, 14, 0);

                foreach ($this->wrapText($this->contentText($reporte) ?: 'Sin contenido registrado.', 92) as $textLine) {
                    $lines[] = $this->line($textLine, 'F1', 10, 14, $textLine === '' ? 5 : 0);
                }
            }

            $lines[] = $this->line('', 'F1', 10, 14, 6);
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

    private function dateText(mixed $date): string
    {
        if (blank($date)) {
            return 'Sin fecha';
        }

        return format_user_date($date) ?: 'Sin fecha';
    }

    private function filename(Paciente $paciente): string
    {
        $patient = Str::slug($paciente->nombre_completo ?: 'paciente') ?: 'paciente';
        $folio = Str::slug($paciente->folio ?: 'sin-folio') ?: 'sin-folio';

        return 'expediente-'.$folio.'-'.$patient.'-'.now()->format('Ymd').'.pdf';
    }
}
