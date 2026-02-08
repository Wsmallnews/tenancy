<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="application-name" content="{{ config('app.name') }}" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @vite('resources/css/app.css')
    </head>

    <body class="antialiased bg-slate-50 flex flex-col">

        {{ $slot }}

        @vite('resources/js/app.js')
    </body>
</html>