<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
        return view('posts.create');
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


        $request->user()->posts()->create([
             'titulo' => $request->titulo,
             'descripcion' => $request->descripcion,
             'imagen' => $request->imagen,
             'user_id' => auth()->user()->id
        ]);


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
