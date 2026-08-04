@extends('layouts.app')

@section('titulo')
    Registrate en DevStagram
@endsection

@section('contenido')
    <div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-2 md:items-center md:gap-10">
        <div class="overflow-hidden rounded-xl">
          <img src="{{ asset('img/registrar.jpg') }}" alt="Crear una cuenta en DevStagram" class="h-56 w-full object-cover sm:h-72 md:h-full">
        </div>

        <div class="rounded-xl bg-white p-5 shadow-xl sm:p-8">
            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf
                <div class="mb-5">
                    <label for="username" class="mb-2 block uppercase text-gray-500 font-bold">
                        Nombre
                    </label>
                    <input 
                        id="name"
                        name="name"
                        type="text"
                        placeholder="Tu Nombre"
                        class="border p-3 w-full rounded-lg @error('name') border-red-500 @enderror"
                        value="{{ old('name') }}"
                    /> 
                    @error('name')
                       <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> 
                        {{ $message}}
                       </p>
                    @enderror
                </div>
           
                <div class="mb-5">
                    <label for="name" class="mb-2 block uppercase text-gray-500 font-bold">
                        Username
                    </label>
                    <input 
                        id="username"
                        name="username"
                        type="text"
                        placeholder="Tu nombre de usuario"
                        class="border p-3 w-full rounded-lg @error('username') border-red-500 @enderror"
                        value="{{ old('username') }}"
                    />

                    @error('username')
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> 
                        {{ $message}}
                    </p>
                 @enderror
                </div>

                <div class="mb-5">
                    <label for="email" class="mb-2 block uppercase text-gray-500 font-bold">
                       Email
                    </label>
                    <input 
                        id="email"
                        name="email"
                        type="email"
                        placeholder="Tu correo de registro"
                        class="border p-3 w-full rounded-lg @error('email') border-red-500 @enderror"
                        value="{{ old('email') }}"
                    />
                    @error('email')
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                        {{ $message}}
                        </p>
                 @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="mb-2 block uppercase text-gray-500 font-bold">
                       Password
                    </label>
                    <input 
                        id="password"
                        name="password"
                        type="password"
                        placeholder="Tu contraseña"
                        class="border p-3 w-full rounded-lg @error('password') border-red-500  @enderror"
                    />
                    @error('password')
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center"> 
                        {{ $message}}
                    </p>
                 @enderror
                </div>

                <div class="mb-5">
                    <label for="password_confirmation" class="mb-2 block uppercase text-gray-500 font-bold">
                     Repetir Password
                    </label>
                    <input 
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        placeholder="Repite tu contraseña"
                        class="border p-3 w-full rounded-lg @error('password_confirmation') border-red-500 @enderror"                  
                    />
                    @error('password_confirmation')
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">
                        {{ $message}}
                        </p>
                 @enderror
                </div>

                <input
                    type="submit"
                    value="Crear Cuenta"
                    class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg"
                   /> 
            </form>
        </div>
    </div>
@endsection
