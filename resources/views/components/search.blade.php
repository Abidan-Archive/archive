<form action="{{route('search')}}" method="GET" {{ $attributes->merge(['class' => 'w-2/3 lg:max-w-[600px] flex flex-col'])}}>
    <label for="search" class="sr-only">Search</label>
    <div class="relative my-4 flex flex-row justify-center shadow-md">
        <div class="flex-1 py-1 border border-r-none border-gray-800 focus-within:border-violet-500 focus-within:border-2 focus-within:ring-1 ring-abidan-300 box-border flex bg-black items-stretch rounded-l-sm border-box">
            <input id="search"
                type="text"
                name="q"
                placeholder="Information Requested..."
                autocomplete="off"
                aria-label="Search"
                class="w-full mx-1 placeholder-neutral-700 bg-neutral-900 rounded-sm border-none focus:ring-0 hover:ring-0"
                />
        </div>
        <button type="submit" class="bg-violet-600 hover:bg-violet-500 flex-0 h-[60px] w-[80px] rounded-r-sm">
            <div class="flex justify-center items-center">
                <x-icon.search class="w-6 h-6 text-neutral-900 bold"/>
            </div>
        </button>
    </div>
    <!-- Advanced Search -->
    {{-- <button>V</button> --}}
    {{-- <div class="bg-gray-300"> --}}
        {{-- More stuff --}}
    {{-- </div> --}}
</form>



{{-- primary color --}}
{{-- hover color --}}
{{-- visited color --}}

{{-- thematic styling on nav --}}
