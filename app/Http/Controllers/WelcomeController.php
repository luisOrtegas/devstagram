<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('auth.welcome', [
            'user' => $request->user(),
            'redirectUrl' => route('posts.index', $request->user()->username),
        ]);
    }
}
