<?php

namespace App\Services;

use Stevebauman\Purify\Facades\Purify;

class HtmlPurifierService
{
    /**
     * Limpia contenido HTML generado por un editor de texto enriquecido.
     */
    public function clean(string $html): string
    {
        return Purify::clean($html);
    }
}
