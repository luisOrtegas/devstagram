@extends('layouts.app')

@section('titulo', 'Buscar perfiles')

@section('contenido')
    <div class="mx-auto max-w-4xl px-4">
        <form action="{{ route('users.search') }}" method="GET" class="mb-8 flex flex-col gap-3 sm:mb-10 sm:flex-row">
            <label for="q" class="sr-only">Nombre o usuario</label>
            <input
                id="q"
                name="q"
                type="search"
                value="{{ $query }}"
                placeholder="Busca por nombre o usuario"
                maxlength="50"
                autofocus
                class="w-full rounded-lg border border-gray-300 bg-white p-3 shadow-sm focus:border-sky-500 focus:outline-none"
            />
            <button
                type="submit"
                class="w-full rounded-lg bg-sky-600 px-6 py-3 font-bold uppercase text-white transition-colors hover:bg-sky-700 sm:w-auto"
            >
                Buscar
            </button>
        </form>

        <nav class="mb-8" aria-label="Buscar perfiles por letra inicial">
            <p class="mb-3 text-center text-sm font-bold text-gray-600">Buscar por la primera letra del nombre</p>
            <div class="flex flex-wrap justify-center gap-2">
                @foreach (range('A', 'Z') as $inicial)
                    <a
                        href="{{ route('users.search', array_filter(['q' => $query, 'letra' => $inicial])) }}"
                        class="flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 text-sm font-bold transition {{ $letra === $inicial ? 'border-sky-600 bg-sky-600 text-white' : 'border-gray-300 bg-white text-gray-700 hover:border-sky-600 hover:bg-sky-50 hover:text-sky-700' }}"
                        aria-label="Buscar nombres que comienzan con {{ $inicial }}"
                        @if ($letra === $inicial) aria-current="true" @endif
                    >
                        {{ $inicial }}
                    </a>
                @endforeach
            </div>

            @if ($query !== '' || $letra !== '')
                <div class="mt-4 text-center">
                    <a href="{{ route('users.search') }}" class="text-sm font-bold text-sky-700 hover:underline">
                        Limpiar búsqueda
                    </a>
                </div>
            @endif
        </nav>

        @if ($query === '' && $letra === '')
            <div class="rounded-xl border border-gray-200 bg-white p-8 text-center shadow-sm">
                <p class="font-bold text-gray-700">Busca un perfil de Devstagram.</p>
                <p class="mt-2 text-sm text-gray-500">Escribe un nombre, un usuario o selecciona una letra.</p>
            </div>
        @else
            <p class="mb-6 text-gray-600">
                @if ($query !== '' && $letra !== '')
                    Resultados para <span class="font-bold">{{ $query }}</span> cuyo nombre comienza con <span class="font-bold">{{ $letra }}</span>
                @elseif ($query !== '')
                    Resultados para <span class="font-bold">{{ $query }}</span>
                @else
                    Perfiles cuyo nombre comienza con <span class="font-bold">{{ $letra }}</span>
                @endif
            </p>

            @if ($usuarios->count())
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($usuarios as $usuario)
                        <a
                            href="{{ route('posts.index', $usuario->username) }}"
                            class="flex min-w-0 items-center gap-4 rounded-xl bg-white p-4 shadow transition hover:-translate-y-1 hover:shadow-lg sm:p-5"
                        >
                            <img
                                src="{{ $usuario->imagen ? asset('perfiles/' . $usuario->imagen) : asset('img/devstagram-icon.svg') }}"
                                alt="Perfil de {{ $usuario->username }}"
                                class="h-16 w-16 shrink-0 rounded-full border border-gray-200 object-cover"
                                width="64"
                                height="64"
                                style="width: 4rem; height: 4rem; min-width: 4rem; max-width: 4rem;"
                            />
                            <div class="min-w-0">
                                <p class="truncate font-bold text-gray-800">{{ $usuario->name }}</p>
                                <p class="truncate text-sm text-gray-500">{{ $usuario->username }}</p>
                                <p class="mt-2 text-xs text-gray-500">
                                    {{ $usuario->followers_count }} seguidores · {{ $usuario->posts_count }} publicaciones
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>

                <nav class="mt-8" role="navigation" aria-label="Paginación de perfiles">
                    <div class="flex flex-wrap items-center justify-center gap-2">
                        @if ($usuarios->onFirstPage())
                            <span class="cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-bold text-gray-400">Anterior</span>
                        @else
                            <a href="{{ $usuarios->previousPageUrl() }}" rel="prev" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:border-sky-600 hover:bg-sky-50 hover:text-sky-700">Anterior</a>
                        @endif

                        @foreach ($usuarios->getUrlRange(1, $usuarios->lastPage()) as $pagina => $url)
                            @if ($pagina === $usuarios->currentPage())
                                <span aria-current="page" class="flex h-10 min-w-10 items-center justify-center rounded-lg bg-sky-600 px-3 text-sm font-black text-white">{{ $pagina }}</span>
                            @else
                                <a href="{{ $url }}" class="flex h-10 min-w-10 items-center justify-center rounded-lg border border-gray-300 bg-white px-3 text-sm font-bold text-gray-700 hover:border-sky-600 hover:bg-sky-50 hover:text-sky-700" aria-label="Ir a la página {{ $pagina }}">{{ $pagina }}</a>
                            @endif
                        @endforeach

                        @if ($usuarios->hasMorePages())
                            <a href="{{ $usuarios->nextPageUrl() }}" rel="next" class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-bold text-gray-700 hover:border-sky-600 hover:bg-sky-50 hover:text-sky-700">Siguiente</a>
                        @else
                            <span class="cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-sm font-bold text-gray-400">Siguiente</span>
                        @endif
                    </div>

                    <p class="mt-3 text-center text-sm text-gray-500">
                        Mostrando {{ $usuarios->firstItem() }}–{{ $usuarios->lastItem() }} de {{ $usuarios->total() }} perfiles
                    </p>
                </nav>
            @else
                <div class="rounded-xl bg-white p-10 text-center shadow">
                    <p class="font-bold text-gray-700">No encontramos perfiles.</p>
                    <p class="mt-2 text-sm text-gray-500">Prueba con otro nombre o usuario.</p>
                </div>
            @endif
        @endif
    </div>
@endsection
