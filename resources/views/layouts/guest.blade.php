@include('gnome')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;400;600;700&display=swap">

        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-zinc-900 dark:text-white">
        <div class="min-h-screen">
            @include('layouts.navigation')

            <main class="">
                <div class="w-1/4 pt-16 mx-auto">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>
