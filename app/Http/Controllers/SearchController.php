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
        ]);

        $query = trim((string) $request->query('q', ''));

        $usuarios = null;

        if ($query !== '') {
            $usuarios = User::query()
                ->whereKeyNot(auth()->id())
                ->where(function ($users) use ($query) {
                    $users->where('username', 'like', "%{$query}%")
                        ->orWhere('name', 'like', "%{$query}%");
                })
                ->withCount(['followers', 'posts'])
                ->orderBy('username')
                ->paginate(12)
                ->withQueryString();
        }

        return view('users.search', [
            'query' => $query,
            'usuarios' => $usuarios,
        ]);
    }
}
