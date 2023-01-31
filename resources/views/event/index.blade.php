<x-app-layout>
    <h2 class="mb-4 text-5xl bg-midnight p-4">Events</h2>
    <div class="container mx-auto">
        <div class="flex flex-col gap-5">
            @foreach($events as $event)
                <section class="border rounded border-gray-200 p-4">
                    <a href="{{ route('event.show', $event) }}">{{ $event->name }}</a>
                    <p>{{ $event->date->diffForHumans() }}</p>
                    <p>{{ $event->location }}</p>
                </section>
            @endforeach
        </div>
    </div>
</x-app-layout>
