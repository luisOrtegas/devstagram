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

        @if ($query !== '')
            <p class="mb-6 text-gray-600">
                Resultados para <span class="font-bold">{{ $query }}</span>
            </p>
        @else
            <p class="mb-6 text-gray-600">Explora otros perfiles de Devstagram.</p>
        @endif

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

            <div class="mt-8">
                {{ $usuarios->links() }}
            </div>
        @else
            <div class="rounded-xl bg-white p-10 text-center shadow">
                <p class="font-bold text-gray-700">No encontramos perfiles.</p>
                <p class="mt-2 text-sm text-gray-500">Prueba con otro nombre o usuario.</p>
            </div>
        @endif
    </div>
@endsection
