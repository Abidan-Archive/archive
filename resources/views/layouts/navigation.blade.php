<nav x-data="{ open: false }" class="fixed z-50 top-0 left-0 h-16 w-full bg-midnight border-l-5 border-abidan-400 text-white drop-shadow-md">
    <!-- Primary Navigation Menu -->
    <div class="px-4 sm:px-6">
        <div class="flex justify-between h-16 w-full">
            <div class="flex place-start">
                <!-- Logo -->
                <a href="{{ route('home') }}">
                    <div class="flex flex-row h-full justify-start items-center">
                        @include('components.application-logo')
                        <h1 class="shrink-0 text-3xl px-2">Abidan Archive</h1>
                    </div>
                </a>
            </div>

                <!-- Navigation Links -->
                <div class="place-end space-x-6 sm:-my-px sm:ml-10 sm:flex hidden ">
                    <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                        <x-icon.home class="px-1" /> {{ __('Home') }}
                    </x-nav-link>
                    <x-nav-link :href="route('event.index')" :active="request()->routeIs('event.index')">
                        <x-icon.calendar class="px-1" /> {{ __('Events') }}
                    </x-nav-link>
                    <x-nav-link :href="route('tag.index')" :active="request()->routeIs('tag.index')">
                        <x-icon.hashtag class="px-1" /> {{ __('Tags') }}
                    </x-nav-link>
                    <x-nav-link :href="config('app.links.wiki')">
                        <x-icon.globe class="px-1" /> {{ __('Wiki') }}
                    </x-nav-link>
                    <x-nav-link :href="config('app.links.discord')">
                        <x-icon.discord class="px-1" /> {{ __('Discord') }}
                    </x-nav-link>
                    <div class="flex items-centered">
                        <label class="sr-only">Theme</label>
                        <button id="theme-button" type="button" >
                            <x-icon.sun  class="inline dark:hidden" />
                            <x-icon.moon class="hidden dark:inline" />
                        </button>
                    </div>
                    <div class="h-full border-l"></div>
                    <!-- User Settings -->
                    @auth
                        <x-nav-link :href="route('login')">
                            <x-icon.user class="px-1"/> {{ Auth::user()->name }}
                        </x-nav-link>
                    @endauth
                    @guest
                        <x-nav-link :href="route('login')">
                            <x-icon.login class="px-1"/>{{__('Sign In')}}
                        </x-nav-link>
                    @endguest
                </div>
            {{-- <!-- Settings Dropdown --> --}}
            {{-- @auth --}}
                {{-- <div class="hidden sm:flex sm:items-center sm:ml-6"> --}}
                    {{-- <x-dropdown align="right" width="48"> --}}
                        {{-- <x-slot name="trigger"> --}}
                            {{-- <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out"> --}}
                                {{-- <div>{{ Auth::user()->name }}</div> --}}

                                {{-- <div class="ml-1"> --}}
                                    {{-- <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"> --}}
                                        {{-- <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /> --}}
                                    {{-- </svg> --}}
                                {{-- </div> --}}
                            {{-- </button> --}}
                        {{-- </x-slot> --}}

                        {{-- <x-slot name="content"> --}}
                            {{-- <!-- Authentication --> --}}
                            {{-- <form method="POST" action="{{ route('logout') }}"> --}}
                                {{-- @csrf --}}

                                {{-- <x-dropdown-link :href="route('logout')" --}}
                                        {{-- onclick="event.preventDefault(); --}}
                                                    {{-- this.closest('form').submit();"> --}}
                                    {{-- {{ __('Log Out') }} --}}
                                {{-- </x-dropdown-link> --}}
                            {{-- </form> --}}
                        {{-- </x-slot> --}}
                    {{-- </x-dropdown> --}}
                {{-- </div> --}}
            {{-- @endauth --}}


            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @endauth
    </div>
</nav>
