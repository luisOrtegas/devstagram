<div>
    @if ($posts->count())
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                <article class="flex h-full flex-col overflow-hidden rounded-xl bg-white shadow transition hover:-translate-y-1 hover:shadow-lg">
                    <a
                        href="{{ route('posts.show', ['post' => $post, 'user' => $post->user]) }}"
                        class="flex aspect-square items-center justify-center bg-gray-950"
                    >
                        <img
                            src="{{ asset('uploads/' . $post->imagen) }}"
                            alt="Imagen de la publicación {{ $post->titulo }}"
                            class="h-full w-full object-contain"
                            loading="lazy"
                        >
                    </a>

                    <div class="flex flex-1 flex-col p-4">
                        <div class="mb-3 flex items-center justify-between gap-3 text-sm text-gray-500">
                            <a
                                href="{{ route('posts.index', $post->user) }}"
                                class="min-w-0 truncate font-bold text-gray-800 hover:text-sky-600"
                            >
                                {{ $post->user->name }}
                            </a>
                            <span class="shrink-0">{{ $post->created_at->diffForHumans() }}</span>
                        </div>

                        @unless (\Illuminate\Support\Str::startsWith($post->titulo, 'Galería Devstagram'))
                            <h3 class="break-words text-lg font-black text-gray-900">
                                {{ $post->titulo }}
                            </h3>
                        @endunless

                        @if ($post->descripcion && !\Illuminate\Support\Str::startsWith($post->titulo, 'Galería Devstagram'))
                            <p class="mt-2 whitespace-pre-line break-words text-sm leading-6 text-gray-600">{{ $post->descripcion }}</p>
                        @endif

                        <div class="mt-auto flex items-center gap-5 border-t border-gray-100 pt-4 text-sm font-bold text-gray-600">
                            <span class="flex items-center gap-1" aria-label="{{ $post->likes_count }} Me gusta">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M12 21s-9-4.78-9-12c0-2.49 2.1-4.5 4.69-4.5 1.94 0 3.6 1.13 4.31 2.73.72-1.6 2.38-2.73 4.31-2.73C18.9 4.5 21 6.51 21 9c0 7.22-9 12-9 12Z"/>
                                </svg>
                                {{ $post->likes_count }} Me gusta
                            </span>

                            <span class="flex items-center gap-1" aria-label="{{ $post->comentarios_count }} comentarios">
                                <svg class="h-5 w-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.63 9.75h6.74m-6.74 3h4.5M21 12a8.25 8.25 0 0 1-9.57 8.15 8.24 8.24 0 0 1-3.9-1.72L3 19.5l1.07-4.53A8.25 8.25 0 1 1 21 12Z"/>
                                </svg>
                                {{ $post->comentarios_count }} comentarios
                            </span>
                        </div>

                        @auth
                            @if ($post->user_id === auth()->id())
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="mt-4 border-t border-gray-100 pt-4">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="w-full rounded-lg bg-red-500 px-4 py-2 text-sm font-bold text-white transition hover:bg-red-600">
                                        Eliminar publicación
                                    </button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </article>
            @endforeach
        </div>

        <div class="my-10">
            {{ $posts->links() }}
        </div>
    @else
        <p class="rounded-lg bg-white p-10 text-center shadow">
            No hay publicaciones. Sigue a alguien para ver sus publicaciones.
        </p>
    @endif
</div>
