<?php

namespace App\Services;

class SimpleTextPdf
{
    private const PAGE_WIDTH = 612;
    private const PAGE_HEIGHT = 792;
    private const MARGIN = 54;

    /**
     * @param  array<int, array{text: string, font: string, size: int, leading: int, after: int}>  $lines
     */
    public function make(array $lines): string
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
