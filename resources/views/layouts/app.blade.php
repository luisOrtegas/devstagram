<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @stack('styles')
        <title>DevStagram - @yield('titulo')</title>
        @livewireStyles
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" type="image/svg+xml" href="{{ asset('img/devstagram-icon.svg') }}">
        <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    </head>
    <body class="flex min-h-screen flex-col bg-gray-100">
        @if (session('welcome'))
            <div class="devstagram-welcome" role="status" aria-live="polite">
                <div class="devstagram-welcome__content">
                    <div class="devstagram-welcome__glow" aria-hidden="true"></div>
                    <img
                        src="{{ asset('img/devstagram-icon.svg') }}"
                        alt="DevStagram"
                        class="devstagram-welcome__icon"
                    >
                    <p class="devstagram-welcome__title">Bienvenido, {{ auth()->user()->name }}</p>
                    <p class="devstagram-welcome__message">Tu comunidad está lista.</p>
                </div>
            </div>
        @endif

        <header class="border-b bg-white shadow-sm">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-2xl font-black text-gray-900 sm:text-3xl">
                        <img src="{{ asset('img/devstagram-icon.svg') }}" alt="" class="h-10 w-10 rounded-xl sm:h-11 sm:w-11">
                        <span>DevStagram</span>
                    </a>

                    <a href="{{ route('home') }}" class="rounded-lg p-2 text-gray-600 hover:bg-gray-100" aria-label="Ir al inicio">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" />
                        </svg>
                    </a>
                </div>

                @auth
                    <nav class="grid grid-cols-2 gap-2 sm:flex sm:flex-wrap sm:items-center sm:justify-end" aria-label="Navegación principal">
                        <a href="{{ route('users.search') }}" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm font-bold uppercase text-gray-600 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            Buscar
                        </a>

                        <a href="{{ route('posts.create') }}" class="flex items-center justify-center gap-2 rounded-lg border px-3 py-2 text-sm font-bold uppercase text-gray-600 hover:bg-gray-50">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                            Crear
                        </a>

                        <a href="{{ route('posts.index', auth()->user()->username) }}" class="flex min-w-0 items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm font-bold text-gray-600 hover:bg-gray-100">
                            <span class="truncate">{{ auth()->user()->username }}</span>
                            <img
                                src="{{ auth()->user()->imagen ? asset('perfiles/' . auth()->user()->imagen) : asset('img/devstagram-icon.svg') }}"
                                alt="Perfil de {{ auth()->user()->name }}"
                                class="h-8 w-8 shrink-0 rounded-full border border-gray-200 object-cover"
                            >
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full rounded-lg px-3 py-2 text-sm font-bold uppercase text-gray-600 hover:bg-gray-100">
                                Cerrar sesión
                            </button>
                        </form>
                    </nav>
                @endauth

                @guest
                    <nav class="flex items-center justify-end gap-2" aria-label="Acceso">
                        <a class="rounded-lg px-3 py-2 text-sm font-bold uppercase text-gray-600 hover:bg-gray-100" href="{{ route('login') }}">Ingresar</a>
                        <a class="rounded-lg bg-sky-600 px-3 py-2 text-sm font-bold uppercase text-white hover:bg-sky-700" href="{{ route('register') }}">Crear cuenta</a>
                    </nav>
                @endguest
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-8 sm:px-6 sm:py-10 lg:px-8">
            @if (trim($__env->yieldContent('titulo')) !== '')
                <h2 class="mb-8 break-words text-center text-2xl font-black sm:mb-10 sm:text-3xl">
                    @yield('titulo')
                </h2>
            @endif
            @yield('contenido')
        </main>

        <footer class="mt-10 px-4 py-6 text-center text-sm font-bold uppercase text-gray-500 sm:text-base">
            DevStagram - Todos los derechos reservados {{ now()->year }}
        </footer>

        @livewireScripts
    </body>
</html>
