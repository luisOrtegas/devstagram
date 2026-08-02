@extends('layouts.app')

@section('titulo', \Illuminate\Support\Str::startsWith($post->titulo, 'Galería Devstagram') ? '' : $post->titulo)

@section('contenido')
    <article class="mx-auto grid max-w-6xl overflow-hidden rounded-xl bg-white shadow-lg lg:grid-cols-2">
        <section class="flex flex-col">
            <div class="flex min-h-80 items-center justify-center bg-gray-950">
                <img
                    src="{{ asset('uploads/' . $post->imagen) }}"
                    alt="Imagen de la publicación {{ $post->titulo }}"
                    class="max-h-[70vh] w-full object-contain"
                >
            </div>

            <div class="p-5 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <a href="{{ route('posts.index', $post->user) }}" class="font-black text-gray-900 hover:text-sky-600">
                            {{ $post->user->name }}
                        </a>
                        <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                    </div>

                    @auth
                        <livewire:like-post :post="$post" />
                    @else
                        <p class="font-bold text-gray-700">{{ $post->likes->count() }} Me gusta</p>
                    @endauth
                </div>

                @unless (\Illuminate\Support\Str::startsWith($post->titulo, 'Galería Devstagram'))
                    <h1 class="mt-5 break-words text-2xl font-black text-gray-900">{{ $post->titulo }}</h1>
                @endunless

                @if ($post->descripcion && !\Illuminate\Support\Str::startsWith($post->titulo, 'Galería Devstagram'))
                    <p class="mt-3 whitespace-pre-line break-words leading-7 text-gray-700">{{ $post->descripcion }}</p>
                @endif

                @auth
                    @if ($post->user_id === auth()->id())
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="mt-6">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="rounded-lg bg-red-500 px-4 py-2 font-bold text-white transition hover:bg-red-600">
                                Eliminar publicación
                            </button>
                        </form>
                    @endif
                @endauth
            </div>
        </section>

        <section class="flex min-h-0 flex-col border-t border-gray-200 lg:border-l lg:border-t-0">
            @auth
                <div class="border-b border-gray-200 p-5 sm:p-6">
                    <h2 class="mb-4 text-xl font-black text-gray-900">Agrega un comentario</h2>

                    @if (session('mensaje'))
                        <div class="mb-5 rounded-lg bg-green-500 p-3 text-center font-bold text-white">
                            {{ session('mensaje') }}
                        </div>
                    @endif

                    <form action="{{ route('comentarios.store', ['post' => $post, 'user' => $user]) }}" method="POST">
                        @csrf
                        <label for="comentario" class="mb-2 block font-bold text-gray-600">Comentario</label>
                        <textarea
                            id="comentario"
                            name="comentario"
                            rows="3"
                            maxlength="255"
                            placeholder="Escribe un comentario"
                            class="w-full resize-y rounded-lg border p-3 @error('comentario') border-red-500 @enderror"
                        >{{ old('comentario') }}</textarea>

                        @error('comentario')
                            <p class="mt-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                        @enderror

                        <button type="submit" class="mt-3 w-full rounded-lg bg-sky-600 p-3 font-bold uppercase text-white transition hover:bg-sky-700">
                            Comentar
                        </button>
                    </form>
                </div>
            @endauth

            <div class="p-5 sm:p-6">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h2 class="text-xl font-black text-gray-900">Comentarios</h2>
                    <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-bold text-gray-600">
                        {{ $post->comentarios->count() }}
                    </span>
                </div>

                <div class="max-h-[32rem] space-y-3 overflow-y-auto pr-1">
                    @forelse ($post->comentarios as $comentario)
                        <div class="flex gap-3 rounded-lg border border-gray-200 p-4">
                            <img
                                src="{{ $comentario->user->imagen ? asset('perfiles/' . $comentario->user->imagen) : asset('img/usuario.svg') }}"
                                alt="Foto de {{ $comentario->user->name }}"
                                class="h-10 w-10 shrink-0 rounded-full object-cover"
                                loading="lazy"
                            >
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-baseline justify-between gap-2">
                                    <a href="{{ route('posts.index', $comentario->user) }}" class="break-words font-bold text-gray-900 hover:text-sky-600">
                                        {{ $comentario->user->name }}
                                    </a>
                                    <span class="text-xs text-gray-500">{{ $comentario->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 whitespace-pre-line break-words text-sm leading-6 text-gray-700">{{ $comentario->comentario }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="rounded-lg bg-gray-50 p-8 text-center text-gray-500">Aún no hay comentarios.</p>
                    @endforelse
                </div>
            </div>
        </section>
    </article>
@endsection
