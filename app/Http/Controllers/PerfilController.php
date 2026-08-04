<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
       
       
        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]); 

       $this->validate($request, [
        'name' => ['required', 'string', 'max:30'],
        'username' => ['required','unique:users,username,' .auth()->user()->id, 'min:3',
         'max:20', 'not_in:twitter, editar-perfil, facebook, devstagram'],
        'telefono' => ['nullable', 'string', 'max:30', 'regex:/^[0-9+\s().-]+$/'],
        'direccion' => ['nullable', 'string', 'max:255'],
        'biografia' => ['nullable', 'string', 'max:500'],
        'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:5120'],
       ]);

       if($request->imagen) {

        $imagen = $request->file('imagen');

       $nombreImagen = Str::uuid() . "." . $imagen->extension();

       $imagenServidor = Image::make($imagen);
       $imagenServidor->fit(1000, 1000); 
       
       $imagenPath = public_path('perfiles') . '/' . $nombreImagen;
       $imagenServidor->save($imagenPath);

       }

       //Guardar Cambios
       $usuario = User::find(auth()->user()->id);
       $usuario->name = $request->name;
       $usuario->username = $request->username;
       $usuario->telefono = $request->telefono;
       $usuario->direccion = $request->direccion;
       $usuario->biografia = $request->biografia;
       $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
       $usuario->save();

       //Redireccionar 
       return redirect()
        ->route('perfil.index')
        ->with('mensaje', 'Tu perfil fue actualizado correctamente.');

    }
}
