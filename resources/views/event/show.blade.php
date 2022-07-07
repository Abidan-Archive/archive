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
        @foreach($event->reports as $report)
            <section id="{{ $report->id }}"
                class="border rounded-lg my-4 mx-1 p-2">
                <div class="flex flex-row justify-between">
                    <div>
                        <a href="{{ $report->permalink }}">&#35;{{ $report->id }}</a>
                        @if($report->date)
                            &middot; {{ $report->date->diffForHumans() }}
                        @endif
                        &middot; &hearts; {{ $report->likes }}
                    </div>
                    <div class="hidden flex-none">
                        <a href="#">Copy</a>
                        <a href="#">Share</a>
                    </div>
                </div>

                <div class="dialogues">
                    @foreach($report->dialogues as $dialogue)
                        <dl>
                            <dt>{{ $dialogue->speaker }}</dt>
                            <dd>{!! $dialogue->lineHtml !!}</dd>
                        </dl>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</x-app-layout>
