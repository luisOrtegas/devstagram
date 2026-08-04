<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowerController extends Controller
{
    public function store(Request $request, User $user)
    {
       abort_if($request->user()->is($user), 422, 'No puedes seguirte a ti mismo.');

       $user->followers()->syncWithoutDetaching([$request->user()->id]);

       return back();
    } 

    public function destroy(Request $request, User $user)
    {
        $user->followers()->detach($request->user()->id);

        return back();
    }
}
