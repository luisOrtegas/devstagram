@extends('layouts.app')

@section('titulo')
    Perfil de usuario
@endsection

@section('contenido')
    <div class="mx-auto max-w-3xl">
        @if (session('mensaje'))
            <div class="mb-6 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center font-bold text-green-800" role="status">
                {{ session('mensaje') }}
            </div>
        @endif

        <div class="rounded-xl bg-white p-5 shadow sm:p-8">
            <div class="mb-8 flex flex-col items-center gap-4 border-b border-gray-200 pb-8 sm:flex-row">
                <img
                    src="{{ auth()->user()->imagen ? asset('perfiles/' . auth()->user()->imagen) : asset('img/devstagram-icon.svg') }}"
                    alt="Perfil de {{ auth()->user()->name }}"
                    class="rounded-full border border-gray-200 object-cover"
                    width="96"
                    height="96"
                    style="width: 6rem; height: 6rem; min-width: 6rem; max-width: 6rem;"
                >
                <div class="text-center sm:text-left">
                    <p class="text-xl font-black text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-gray-500">{{ auth()->user()->username }}</p>
                    <p class="mt-2 text-xs text-gray-500">Tu teléfono y dirección permanecen dentro de la configuración de tu cuenta.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('perfil.store') }}" enctype="multipart/form-data">
                @csrf 

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="name" class="mb-2 block font-bold uppercase text-gray-500">Nombre</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            maxlength="30"
                            value="{{ old('name', auth()->user()->name) }}"
                            class="w-full rounded-lg border p-3 @error('name') border-red-500 @enderror"
                            required
                        />
                        @error('name')
                            <p class="my-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                    <label for="username" class="mb-2 block uppercase text-gray-500 font-bold">
                        Usuario
                    </label>
                    <input 
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Tu Nombre de Usuario"
                        class="border p-3 w-full rounded-lg @error('username') border-red-500 @enderror"
                        value="{{ old('username', auth()->user()->username) }}"
                        required
                    /> 
                    @error('username')
                       <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> 
                        {{ $message}}
                       </p>
                    @enderror
                    </div>

                    <div>
                        <label for="telefono" class="mb-2 block font-bold uppercase text-gray-500">Teléfono</label>
                        <input
                            id="telefono"
                            name="telefono"
                            type="tel"
                            maxlength="30"
                            placeholder="Ej. +52 55 1234 5678"
                            value="{{ old('telefono', auth()->user()->telefono) }}"
                            class="w-full rounded-lg border p-3 @error('telefono') border-red-500 @enderror"
                        />
                        @error('telefono')
                            <p class="my-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="direccion" class="mb-2 block font-bold uppercase text-gray-500">Dirección</label>
                        <input
                            id="direccion"
                            name="direccion"
                            type="text"
                            maxlength="255"
                            placeholder="Tu dirección"
                            value="{{ old('direccion', auth()->user()->direccion) }}"
                            class="w-full rounded-lg border p-3 @error('direccion') border-red-500 @enderror"
                        />
                        @error('direccion')
                            <p class="my-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-5">
                    <label for="biografia" class="mb-2 block font-bold uppercase text-gray-500">Biografía</label>
                    <textarea
                        id="biografia"
                        name="biografia"
                        rows="4"
                        maxlength="500"
                        placeholder="Cuéntanos algo sobre ti"
                        class="w-full resize-y rounded-lg border p-3 @error('biografia') border-red-500 @enderror"
                    >{{ old('biografia', auth()->user()->biografia) }}</textarea>
                    @error('biografia')
                        <p class="my-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="imagen" class="mb-2 block uppercase text-gray-500 font-bold">
                        Foto de perfil
                    </label>
                    <input 
                        id="imagen"
                        name="imagen"
                        type="file"
                        class="border p-3 w-full rounded-lg"
                        value=""
                        accept=".jpg, .jpeg, .png, .gif, .webp"
                    /> 
                    @error('imagen')
                        <p class="my-2 rounded-lg bg-red-500 p-2 text-center text-sm text-white">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="mt-7 w-full cursor-pointer rounded-lg bg-sky-600 p-3 font-bold uppercase text-white transition-colors hover:bg-sky-700">
                    Guardar cambios
                </button>
            </form>
        </div>
    </div>
@endsection
