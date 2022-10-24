<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @method('styles')
        <title>DevStagram - @yield('titulo')</title>
        <script src="{{ asset('js/app.js') }}" defer></script>
        @livewireStyles
        @vite(['resources/css/app.css'])
        @vite(['resources/js/app.js']) 
        <link rel="icon" href="{{asset('ruta')}}">
    </head>
    <body class="bg-gray-100">
        <header class="p-5 border-b bg-white shadow">
            <div class="container mx-auto flex justify-between items-center">
            <a href="{{ route('home') }}" class="text-3xl font-black">
                      DevStagram 
            </a>
            
            <div class="container mx-auto flex justify-between items-center">     
               <a href="{{ route('home') }}" class="text-3xl font-black">  
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                  </svg>   
              
            </a>

            @auth
            <nav class="flex gap-2 items-center">
                        <a class="flex items-center gap-2 bg-white border p-2 text-gray-600 rounded 
                        text-sm uppercase font-bold cursor-pointer" href="/"
                        >
                        
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        
                     Buscar
                 </a>  

                <a class="flex items-center gap-2 bg-white border p-2 text-gray-600 rounded 
                    text-sm uppercase font-bold cursor-pointer" href="{{ route('posts.create') }}"
                    >
                    
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                      </svg>
                         
                    Crear
                </a>   

                <a class="font-bold text-gray-600 text-sm" 
                   href="{{ route('posts.index', auth()->user()->username ) }}">
                    Hola: <span class="font-normal">{{ auth()->user()->username }} 
                       </span> 
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                <button type="submit" class="font-bold uppercase
                 text-gray-600 text-sm">
                    Cerrar Session
                </button>
                </form> 
               </nav>
            @endauth

            @guest
            <nav class="flex gap-2 items-center">
                <a class="font-bold uppercase text-gray-600 text-sm"  href="{{ route('login') }}">Login</a>
                <a class="font-bold uppercase text-gray-600 text-sm" href="{{ route('register') }}">Crear Cuenta</a>
               </nav>
            @endguest          
         </div>
      </div> 
 </header>

        <main class="container mx-auto mt-10"> 
            <h2 class="font-black text-center text-3xl mb-10">
            @yield('titulo')
           </h2>
           @yield('contenido')
        </main> 

        <footer class="mt-10 text-center p-5 text-gray-500 font-bold uppercase">
            DevStagram - Todos los derechos reservados 
            {{ now()->year }}
        </footer>

    @livewireScripts
 </body>
</html>       