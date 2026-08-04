<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comentario;
use App\Notifications\NewCommentNotification;
use Illuminate\Http\Request;

class ComentarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, User $user, Post $post)
    {
        abort_unless($post->user_id === $user->id, 404);

        //Validar
        $this->validate($request, [
            'comentario' => 'required|max:255'
        ]);
        
        //Almacenar el resultado
        $comentario = Comentario::create([
            'user_id' => auth()->user()->id,
            'post_id' => $post->id,
            'comentario' => $request->comentario
        ]);

        if ($post->user_id !== auth()->id()) {
            $post->user->notify(
                new NewCommentNotification($post, $comentario, auth()->user())
            );
        }

        //Imprimir un mensaje
        return back()->with('mensaje', 'Comentario Realizado Correctamente');
    }
}
