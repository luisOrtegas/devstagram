<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $request->validate([
            'q' => ['nullable', 'string', 'max:50'],
            'letra' => ['nullable', 'string', 'size:1', 'regex:/^[a-zA-Z]$/'],
        ]);

        $query = trim((string) $request->query('q', ''));
        $letra = strtoupper(trim((string) $request->query('letra', '')));

        $usuarios = null;

        if ($query !== '' || $letra !== '') {
            $usuarios = User::query()
                ->whereKeyNot(auth()->id())
                ->when($query !== '', function ($builder) use ($query) {
                    $builder->where(function ($users) use ($query) {
                        $users->where('username', 'like', "%{$query}%")
                            ->orWhere('name', 'like', "%{$query}%");
                    });
                })
                ->when($letra !== '', function ($builder) use ($letra) {
                    $builder->where('name', 'like', "{$letra}%");
                })
                ->withCount(['followers', 'posts'])
                ->orderBy('name')
                ->orderBy('username')
                ->paginate(12)
                ->withQueryString();
        }

        return view('users.search', [
            'query' => $query,
            'letra' => $letra,
            'usuarios' => $usuarios,
        ]);
    }
}
