<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Notifications\MentionedInPostNotification;
use App\Notifications\NewPostNotification;
use App\Support\MentionFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index(User $user) 
    {    

        $posts = Post::with('user')
            ->withCount(['likes', 'comentarios'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        
        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
           
        ]);
    }

    public function create() 
    {
        $usuarios = User::query()
            ->whereKeyNot(auth()->id())
            ->orderBy('name')
            ->get(['name', 'username', 'imagen']);

        return view('posts.create', [
            'usuarios' => $usuarios,
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
            'imagen' => ['required', 'string', 'max:255']
        ], [
            'imagen.required' => 'Debes subir una imagen antes de crear la publicación.',
        ]);

        //  Post::create([
        //      'titulo' => $request->titulo,
        //      'descripcion' => $request->descripcion,
        //      'imagen' => $request->imagen,
        //      'user_id' => auth()->user()->id
        //  ]);
        
        // //Otra forma
        // $post = new Post;
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;
        // $post->save();


        $post = $request->user()->posts()->create([
             'titulo' => $request->titulo,
             'descripcion' => $request->descripcion ?? '',
             'imagen' => $request->imagen,
             'user_id' => auth()->user()->id
        ]);

        $mentionedUsernames = MentionFormatter::usernames($post->descripcion);
        $mentionedUsers = User::query()
            ->whereIn('username', $mentionedUsernames)
            ->whereKeyNot($request->user()->id)
            ->get();

        $post->mentions()->sync($mentionedUsers->modelKeys());

        foreach ($mentionedUsers as $mentionedUser) {
            $mentionedUser->notify(
                new MentionedInPostNotification($post, $request->user())
            );
        }

        $followers = $request->user()
            ->followers()
            ->whereNotIn('users.id', $mentionedUsers->pluck('id'))
            ->get();

        if ($followers->isNotEmpty()) {
            Notification::send(
                $followers,
                new NewPostNotification($post, $request->user())
            );
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {
        abort_unless($post->user_id === $user->id, 404);

        $post->load(['user', 'likes', 'comentarios.user']);

        return view('posts.show', [
            'post' => $post,
            'user' => $user
        ]);
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        $titulo = $post->titulo;
        $imagen = $post->imagen;

        $post->delete();

        // La imagen solo se elimina si ninguna otra publicación la utiliza.
        if (! Post::where('imagen', $imagen)->exists()) {
            $imagenPath = public_path('uploads/' . $imagen);

            if (File::exists($imagenPath)) {
                File::delete($imagenPath);
            }
        }

        return redirect()
            ->route('posts.index', auth()->user()->username)
            ->with('mensaje', "La publicación «{$titulo}» fue eliminada correctamente.");
    }
}
