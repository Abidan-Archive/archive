<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="w-full flex justify-center items-center h-screen -mt-[75px] -mb-[65px]">
        <form method="GET" action="/search" class="w-full max-w-md">
            <div class="w-full mx-auto relative flex items-center mx-auto text-gray-400 focus-within:text-gray-600">
                <div class="absolute ml-3 pointer-events-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input
                    id="search"
                    type="text"
                    name="q"
                    placeholder="Information Requested..."
                    autocomplete="off"
                    aria-label="Search"
                    class="pl-3 pl-10 px-3 py-2 font-semibold placeholder-gray-500 text-black rounded-2xl border-none ring-2 ring-gray-300 focus:ring-gray-500 focus:ring-2"
                />
            </div>
        </form>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach($events as $event)
                        <a class="block text-lg font-bold" href="{{ route('event.show', $event) }}">{{ $event->name }}</a>
                        {{ $event->date->diffForHumans() }}
                        {{ $event->location ? ' | ' . $event->location : '' }}<br />
                        {{ 'Reports: ' . $event->reports->count() }}
                        <hr />
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
