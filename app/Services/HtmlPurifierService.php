<?php

namespace App\Services;

class HtmlPurifierService
{
    /**
     * Etiquetas HTML permitidas para el contenido generado por el editor
     * enriquecido de anuncios (negrita, cursiva, listas, enlaces, etc.).
     */
    private const ALLOWED_TAGS = '<p><br><b><strong><i><em><u><ul><ol><li><a><h1><h2><h3><h4><blockquote><span><div>';

    /**
     * Limpia contenido HTML generado por un editor de texto enriquecido.
     *
     * Elimina etiquetas no permitidas (scripts, iframes, etc.) para evitar
     * inyección de HTML/JS mientras conserva el formato básico del editor.
     */
    public function clean(string $html): string
    {
        $stripped = strip_tags($html, self::ALLOWED_TAGS);

        // Elimina atributos peligrosos como on*="" y href="javascript:..."
        $stripped = preg_replace('/\s(on\w+)\s*=\s*("[^"]*"|\'[^\']*\')/i', '', $stripped) ?? $stripped;
        $stripped = preg_replace('/href\s*=\s*("|\')\s*javascript:[^"\']*("|\')/i', 'href="#"', $stripped) ?? $stripped;

        return $stripped;
    }
}
