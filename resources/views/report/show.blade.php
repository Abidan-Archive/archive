<x-app-layout>
    <section>
        <h2 class="mb-4 text-5xl bg-midnight p-4">Report #{{ $report->id }}</h2>
        @include('components.report', $report)
    </section>
</x-app-layout>
