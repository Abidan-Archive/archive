<x-app-layout>
    <section class="overflow-hidden lg:h-4/6 md:h-3/5 sm:h-1/2 h-auto shadow-md"
        style="background-image: radial-gradient(ellipse at center, #1B2735 0%, #090A0F 90%)">
        <div id="stars-lg"></div>
        <div id="stars-md"></div>
        <div id="stars-sm"></div>
        <div class="w-full h-full flex justify-center items-center container mx-auto">
            <x-search />
        </div>
    </section>
    <div class="py-8 bg-midnight">
        <div class="container mx-auto flex flex-row">
            <section class="">
                <h2 class="text-4xl">Welcome to the Archive</h2>
                <p>This is a fan run site and is not meant to be official in any context.</p>
                <p>Everything on this site is <b>subject to change</b>. Will can and will change anything to suit the story better.</p>
            </section>
            @guest
                <aside class="text-right flex-1">
                    <h3>Want to contribute?</h3>
                    We are always looking for chroniclers to transcribe audio into new Reports.
                </aside>
            @endguest
        </div>
    </div>
    <div class="container mx-auto">
        <div class="flex flex-row">
            <section class="w-4/5">
                <h2 class="uppercase font-thin text-3xl pb-4">Most Recent Events</h2>
                @foreach($events as $event)
                    {{$event->name}}<br />
                @endforeach
            </section>
        </div>
        <div class="flex flex-row">
            <section class="w-4/5">
                <h2 class="uppercase font-thin text-3xl pb-4">Most Recent Events</h2>
                @foreach($events as $event)
                    {{$event->name}}<br />
                @endforeach
            </section>
        </div>
        <div class="flex flex-row">
            <section class="w-4/5">
                <h2 class="uppercase font-thin text-3xl pb-4">Most Recent Events</h2>
                @foreach($events as $event)
                    {{$event->name}}<br />
                @endforeach
            </section>
        </div>
        <div class="flex flex-row">
            <section class="w-4/5">
                <h2 class="uppercase font-thin text-3xl pb-4">Most Recent Events</h2>
                @foreach($events as $event)
                    {{$event->name}}<br />
                @endforeach
            </section>
        </div>


    </div>

</x-app-layout>
