@extends('layouts.app')

@section('titulo')
    Crea una nueva Publicacion
@endsection 

@section('contenido')
   <div class="mx-auto grid max-w-6xl gap-6 md:grid-cols-2 md:items-center md:gap-10">
      <div class="min-w-0">
         <form action="{{ route('imagenes.store') }}" method="POST" 
          enctype="multipart/form-data" id="dropzone" 
          class="dropzone flex h-64 w-full flex-col items-center justify-center rounded-xl border-2 border-dashed sm:h-80 md:h-96
          ">
             @csrf
         </form>
      </div> 

      <div class="rounded-xl bg-white p-5 shadow-xl sm:p-8 lg:p-10">
        <form action="{{ route('posts.store') }}" method="POST" id="post-form" novalidate>
            @csrf
            <div class="mb-5">
                <label for="titulo" class="mb-2 block uppercase text-gray-500 font-bold">
                    Titulo
                </label>
                <input 
                    id="titulo"
                    name="titulo"
                    type="text"
                    placeholder="Titulo de la Publicacion"
                    class="border p-3 w-full rounded-lg @error('titulo') border-red-500 @enderror"
                    value="{{ old('titulo') }}"
                /> 
                @error('titulo')
                   <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> {{ $message}} </p>
                @enderror
            </div>
            <div class="mb-5">
                <label for="descripcion" class="mb-2 block uppercase text-gray-500 font-bold">
                    Descripcion
                </label>
                <textarea 
                    id="descripcion"
                    name="descripcion"
                    placeholder="Descripción de la publicación. Escribe @ para etiquetar a alguien"
                    aria-controls="mention-results"
                    aria-autocomplete="list"
                    class="min-h-28 w-full rounded-lg border p-3 @error('descripcion') border-red-500
                    @enderror">{{ old('descripcion') }}</textarea> 

                <p class="mt-2 text-sm text-gray-500">
                    Escribe <strong>@</strong> seguido del nombre o usuario para etiquetar a una persona.
                </p>

                <div id="mention-results" class="mt-2 hidden overflow-y-auto rounded-lg border border-gray-200 bg-white shadow-lg" style="max-height: 13rem;" role="listbox" aria-label="Usuarios para etiquetar">
                    @foreach ($usuarios as $usuario)
                        <button
                            type="button"
                            class="mention-option hidden w-full items-center gap-3 border-b border-gray-100 p-3 text-left hover:bg-sky-50 focus:bg-sky-50 focus:outline-none"
                            data-search="{{ \Illuminate\Support\Str::lower($usuario->name . ' ' . $usuario->username) }}"
                            data-username="{{ $usuario->username }}"
                            role="option"
                        >
                            <img
                                src="{{ $usuario->imagen ? asset('perfiles/' . $usuario->imagen) : asset('img/devstagram-icon.svg') }}"
                                alt=""
                                class="shrink-0 rounded-full border border-gray-200 object-cover"
                                width="40"
                                height="40"
                                style="width: 2.5rem; height: 2.5rem; min-width: 2.5rem; max-width: 2.5rem;"
                            >
                            <span class="min-w-0">
                                <span class="block truncate font-bold text-gray-800">{{ $usuario->name }}</span>
                                <span class="block truncate text-sm text-gray-500">@{{ $usuario->username }}</span>
                            </span>
                        </button>
                    @endforeach
                    <p id="mention-empty" class="hidden p-4 text-center text-sm text-gray-500">No encontramos usuarios.</p>
                </div>

                @error('descripcion')
                   <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> {{ $message}} </p>
                @enderror
            </div>

            <div class="mb-5">
                <input name="imagen" type="hidden" value="{{ old('imagen') }}" />
                <p
                    id="image-error"
                    class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center {{ $errors->has('imagen') ? '' : 'hidden' }}"
                >
                    {{ $errors->first('imagen') }}
                </p>
            </div>
      
            <input
            type="submit"
            value="Crear Publicacion"
            class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
           /> 

        </form> 
    </div>   
 </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const results = document.getElementById('mention-results');
        const empty = document.getElementById('mention-empty');
        const description = document.getElementById('descripcion');
        const options = Array.from(document.querySelectorAll('.mention-option'));
        let mentionStart = null;
        let visibleOptions = [];
        let activeIndex = -1;

        if (!results || !description) return;

        function closeSuggestions() {
            results.classList.add('hidden');
            options.forEach(function (option) {
                option.classList.add('hidden');
                option.classList.remove('flex', 'bg-sky-50');
                option.setAttribute('aria-selected', 'false');
            });
            visibleOptions = [];
            activeIndex = -1;
            mentionStart = null;
        }

        function refreshSuggestions() {
            const cursor = description.selectionStart ?? description.value.length;
            const beforeCursor = description.value.slice(0, cursor);
            const match = beforeCursor.match(/(?:^|\s)@([A-Za-z0-9._-]*)$/);

            if (!match) {
                closeSuggestions();
                return;
            }

            const term = match[1].toLocaleLowerCase('es');
            mentionStart = cursor - match[1].length - 1;
            visibleOptions = [];

            options.forEach(function (option) {
                const username = option.dataset.username.toLocaleLowerCase('es');
                const matches = term === '' || username.startsWith(term) || option.dataset.search.includes(term);
                option.classList.toggle('hidden', !matches);
                option.classList.toggle('flex', matches);
                option.classList.remove('bg-sky-50');
                option.setAttribute('aria-selected', 'false');
                if (matches) visibleOptions.push(option);
            });

            results.classList.remove('hidden');
            empty.classList.toggle('hidden', visibleOptions.length > 0);
            activeIndex = -1;
        }

        function insertMention(option) {
            if (mentionStart === null) return;

            const cursor = description.selectionStart ?? description.value.length;
            const before = description.value.slice(0, mentionStart);
            const after = description.value.slice(cursor);
            const mention = '@' + option.dataset.username + ' ';

            description.value = before + mention + after.replace(/^\s*/, '');
            const newCursor = (before + mention).length;
            closeSuggestions();
            description.focus();
            description.setSelectionRange(newCursor, newCursor);
        }

        function activateOption(index) {
            if (visibleOptions.length === 0) return;

            activeIndex = (index + visibleOptions.length) % visibleOptions.length;
            visibleOptions.forEach(function (option, optionIndex) {
                const active = optionIndex === activeIndex;
                option.classList.toggle('bg-sky-50', active);
                option.setAttribute('aria-selected', active ? 'true' : 'false');
            });
            visibleOptions[activeIndex].scrollIntoView({ block: 'nearest' });
        }

        description.addEventListener('input', refreshSuggestions);
        description.addEventListener('click', refreshSuggestions);
        description.addEventListener('keyup', function (event) {
            if (!['ArrowDown', 'ArrowUp', 'Enter', 'Escape'].includes(event.key)) {
                refreshSuggestions();
            }
        });

        description.addEventListener('keydown', function (event) {
            if (results.classList.contains('hidden')) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activateOption(activeIndex + 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activateOption(activeIndex - 1);
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                insertMention(visibleOptions[activeIndex]);
            } else if (event.key === 'Escape') {
                event.preventDefault();
                closeSuggestions();
            }
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                insertMention(option);
            });
        });

        document.addEventListener('click', function (event) {
            if (event.target !== description && !results.contains(event.target)) {
                closeSuggestions();
            }
        });
    });
</script>
@endpush
