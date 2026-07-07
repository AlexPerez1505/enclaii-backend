<x-mail::message>
# {{ $anuncio->titulo }}

{!! $anuncio->contenido !!}

<x-mail::button :url="url('/dashboard')">
Ver en la plataforma
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
