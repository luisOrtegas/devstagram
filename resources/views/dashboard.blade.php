@extends('layouts.app')

@section('titulo')
    Perfil: {{ $user->username }}
@endsection


@section('contenido')

    @if (session('mensaje'))
        <div
            role="status"
            class="mx-auto mb-6 max-w-3xl rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-center font-bold text-green-800"
        >
            {{ session('mensaje') }}
        </div>
    @endif
    
    <div class="flex justify-center">
        <div class="flex w-full max-w-3xl flex-col items-center gap-6 rounded-xl bg-white p-5 shadow sm:p-8 md:flex-row">
          <div class="w-40 shrink-0 sm:w-48">
              <img src="{{ $user->imagen ? asset('perfiles') . '/' . $user->imagen : asset('img/devstagram-icon.svg') }}" alt="Perfil de {{ $user->name }}" class="aspect-square w-full rounded-full object-cover" />
          </div>
          <div class="flex min-w-0 flex-1 flex-col items-center md:items-start">
                 
            <div class="flex items-center gap-2">
              <p class="max-w-full break-words text-2xl text-gray-700">{{ $user->username }}</p>

              @auth
                @if ($user->id === auth()->user()->id) 
                  <a class="text-gray-500 hover:text-gray-600 cursor-pointer" href="{{ route('perfil.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875
                       1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 
                       4.487zm0 0L19.5 7.125" />
                    </svg>
                    
                  </a>
                @endif
              @endauth
            </div>             
              <p class="text-gray-800 text-sm mb-3 font-bold mt-5">
                 {{ $user->followers->count() }}
                <span class="font-normal"> @choice('Seguidor|Seguidores', $user->followers->count() ) </span>
              </p>
              <p class="text-gray-800 text-sm mb-3 font-bold">
                {{ $user->followings->count() }}
                <span class="font-normal">Siguiendo</span>
           </p>     

           <p class="text-gray-800 text-sm mb-3 font-bold">
            {{ $user->posts->count() }}
         <span class="font-normal"> Posts</span>
       </p>
          
       @auth
       @if ( $user->id !== auth()->user()->id)
       @if ( !$user->siguiendo( auth()->user() ))
         <form 
               action="{{ route('users.follow', $user) }}" 
               method="POST">

             @csrf
             <input 
                    type="submit" 
                    class="bg-blue-600 text-white uppercase rounded-lg 
                           px-3 py-1 text-xs font-bold cursor-pointer"
                    value="Seguir"       
                    />
         </form>
        @else 
         <form 
               action="{{ route('users.unfollow', $user) }} "  
               method="POST">

          @csrf
          @method('DELETE')
          <input 
                 type="submit" 
                 class="bg-red-600 text-white uppercase rounded-lg 
                        px-3 py-1 text-xs font-bold cursor-pointer"
                 value="Dejar de Seguir"       
                    />
                  </form>
              @endif  
            @endif
        @endauth
              </div>
            </div>
        </div>

    <section class="mt-10">
        <h2 class="my-8 text-center text-3xl font-black sm:my-10 sm:text-4xl">Publicaciones</h2>
        <x-listar-post :posts="$posts" /> 
      
    </section>

@endsection
