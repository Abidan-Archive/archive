<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event') }}
        </h2>
    </x-slot>

    <div class="container mx-auto">
        {{ $event->name }}<br />
        {{ $event->date->diffForHumans() }}
        @if($event->location)
            &middot;
            @if($event->isLocationLink())
                <a href="{{ $event->location }}">
                    {{ getDomain($event->location) ?? 'Site' }}
                </a>
            @else
                {{ $event->location }}
            @endif
        @endif
        <hr />
        @each('components.report', $event->reports, 'report')
    </div>
</x-app-layout>
