<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>

    <section>
        <x-search class="mx-auto"/>
    </section>
    <section class="container mx-auto py-5 h-screen">
        @forelse($reports as $report)
            @include('components.report', $report)
        @empty
            No reports found on the searched topic.
        @endforelse
    </section>
</x-app-layout>
