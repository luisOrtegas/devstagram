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
                    placeholder="Descripcion de la Publicacion"
                    class="min-h-28 w-full rounded-lg border p-3 @error('descripcion') border-red-500
                    @enderror">{{ old('descripcion') }}</textarea> 

                @error('descripcion')
                   <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> {{ $message}} </p>
                @enderror
            </div>

            <div class="mb-5 rounded-lg border border-gray-200 bg-gray-50 p-4">
                <label for="mention-search" class="mb-2 block font-bold uppercase text-gray-500">
                    Etiquetar a alguien
                </label>
                <p class="mb-3 text-sm text-gray-500">
                    Busca una persona y selecciónala para añadir su usuario a la descripción.
                </p>
                <input
                    id="mention-search"
                    type="search"
                    placeholder="Buscar por nombre o usuario"
                    autocomplete="off"
                    class="w-full rounded-lg border border-gray-300 bg-white p-3"
                />

                <div id="mention-results" class="mt-3 hidden overflow-y-auto rounded-lg border border-gray-200 bg-white" style="max-height: 13rem;">
                    @foreach ($usuarios as $usuario)
                        <button
                            type="button"
                            class="mention-option hidden w-full items-center gap-3 border-b border-gray-100 p-3 text-left hover:bg-sky-50"
                            data-search="{{ \Illuminate\Support\Str::lower($usuario->name . ' ' . $usuario->username) }}"
                            data-username="{{ $usuario->username }}"
                        >
                            <img
                                src="{{ $usuario->imagen ? asset('perfiles/' . $usuario->imagen) : asset('img/devstagram-icon.svg') }}"
                                alt="Perfil de {{ $usuario->name }}"
                                class="shrink-0 rounded-full border border-gray-200 object-cover"
                                width="40"
                                height="40"
                                style="width: 2.5rem; height: 2.5rem; min-width: 2.5rem; max-width: 2.5rem;"
                            >
                            <span class="min-w-0">
                                <span class="block truncate font-bold text-gray-800">{{ $usuario->name }}</span>
                                <span class="block truncate text-sm text-gray-500">{{ $usuario->username }}</span>
                            </span>
                        </button>
                    @endforeach
                    <p id="mention-empty" class="hidden p-4 text-center text-sm text-gray-500">No encontramos usuarios.</p>
                </div>
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
        const search = document.getElementById('mention-search');
        const results = document.getElementById('mention-results');
        const empty = document.getElementById('mention-empty');
        const description = document.getElementById('descripcion');
        const options = Array.from(document.querySelectorAll('.mention-option'));

        if (!search || !results || !description) return;

        search.addEventListener('input', function () {
            const term = search.value.trim().toLocaleLowerCase('es');
            let visible = 0;

            options.forEach(function (option) {
                const matches = term !== '' && option.dataset.search.includes(term);
                option.classList.toggle('hidden', !matches);
                option.classList.toggle('flex', matches);
                if (matches) visible++;
            });

            results.classList.toggle('hidden', term === '');
            empty.classList.toggle('hidden', term === '' || visible > 0);
        });

        options.forEach(function (option) {
            option.addEventListener('click', function () {
                const mention = '@' + option.dataset.username;
                const start = description.selectionStart ?? description.value.length;
                const end = description.selectionEnd ?? start;
                const before = description.value.slice(0, start);
                const after = description.value.slice(end);
                const leadingSpace = before !== '' && !/\s$/.test(before) ? ' ' : '';
                const trailingSpace = after !== '' && !/^\s/.test(after) ? ' ' : '';

                description.value = before + leadingSpace + mention + trailingSpace + after;
                const cursor = (before + leadingSpace + mention + trailingSpace).length;
                description.focus();
                description.setSelectionRange(cursor, cursor);
                search.value = '';
                results.classList.add('hidden');
                options.forEach(function (item) {
                    item.classList.add('hidden');
                    item.classList.remove('flex');
                });
            });
        });
    });
</script>
@endpush
