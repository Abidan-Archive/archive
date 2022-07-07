<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-[#090A0F]">
            <main >
                <section id="stars" >
                    <h1 class="absolute w-full t-20 text-white text-7xl text-center">Abidan Archive</h1>
                    <div id="stars-sm"></div>
                    <div id="stars-md"></div>
                    <div id="stars-lg"></div>

                    <div class="w-full h-screen flex justify-center items-center">
                        <form action="/search" method="GET">
                            <div class="relative flex items-center mx-auto text-gray-400 focus-within:text-gray-600">
                                <div class="w-full mx-auto absolute ml-3 pointer-events-none">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input id="search"
                                    type="text"
                                    name="q"
                                    placeholder="Information Requested..."
                                    autocomplete="off"
                                    aria-label="Search"
                                    class="pl-10 px-3 py-2 font-semibold placeholder-gray-500 text-black rounded-2xl border-none ring-2 ring-gray-300 focus:ring-gray-500 focus:ring-2"
                                    />
                            </div>
                        </form>
                    </div>
                </section>
                <section class="container text-white -mt-20 rounded-lg bg-gray-900 w-2/3 mx-auto p-5">
                    <h3 class="text-lg text-center">Recent Events</h3>
                    @for ($i = 0; $i < 10; $i++)
                        <article class="rounded bg-grey-300">Blah blah blah</article>
                    @endfor
                </section>
            </main>
        </div>
    </body>
</html>
