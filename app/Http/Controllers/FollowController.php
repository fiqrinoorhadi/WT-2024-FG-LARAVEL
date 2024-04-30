<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function following($username)
    {
        $user = User::findOrFail('username',$username);
        dd($user);
    }
}
