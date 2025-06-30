<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
    <head>
        <meta charset="utf-8">

        @stack('seo')

        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>

        @filamentStyles
        @vite('resources/css/app.css')

        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body class="antialiased">
        {{ $slot }}

        @livewire('notifications')
        {{-- @livewire('database-notifications') --}}

        @filamentScripts
        @vite('resources/js/app.js')

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('refresh', () => {
                    window.location.reload();
                });
            });
        </script>
    </body>
</html>