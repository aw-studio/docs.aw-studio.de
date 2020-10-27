<header class="fixed top-0 left-0 z-50 flex items-center w-full h-16 text-white bg-black sm:h-24">
    <div class="container flex items-center justify-between">
        <span class="flex items-center">
            <a href="{{ route('home') }}">
                <b class="mr-2">//* Alle Wetter</b> <span class="hidden sm:inline-block">{{ __('app.claim') }}</span>
            </a>
        </span>
        @auth
            <x-button type="dark" link="/logout" target="">
                <span class="mr-2">Logout</span>
            </x-button>
        @else
            <x-button type="dark" link="/login/github" target="">
                <span class="mr-2">Login</span> @include('icons.github')
            </x-button>
        @endauth
    </div>
</header>