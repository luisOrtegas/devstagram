<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeUser;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    //
    public function index() 
    {
        return view('auth.register');
    }

    public function store(Request $request) 
    {
        // dd($request); 
        // dd($request->get('email')); 

        //Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        //Validar
        $this->validate($request, [
            'name' => 'required|max:30',
            'username' => 'required|unique:users|min:3|max:20',
            'email' => 'required|unique:users|email|max:60',
            'password' => 'required|confirmed|min:6'
        ]);

        
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make( $request->password )
        ]);


        // //Autenficar usuario
        // auth()->attempt([
        //     'email' => $request->email,
        //     'password' => $request->password
        // ]);


        //Otra forma de autenticar al usuario
        auth()->login($user);

        //Enviar correo de bienvenida mediante el servicio configurado (Mailtrap en local)
        Mail::to($user->email)->send(new WelcomeUser($user));


        //Redirecionar al usuario
        return redirect()->route('welcome');
    } 
    
}
