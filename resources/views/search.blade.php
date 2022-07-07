<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Search') }}
        </h2>
    </x-slot>

    <section class="container mx-auto">
        @each('components.report', $reports, 'report')
        @empty($reports)
            No reports found on the searched topic.
        @endempty
    </section>
</x-app-layout>
