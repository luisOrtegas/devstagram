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
