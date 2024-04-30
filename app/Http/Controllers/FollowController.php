<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function index($username)
    {
        $user = User::where('username',$username)->first();

        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ]);
        }elseif(Auth::user()->username == $username){
            return response()->json([
                'message' => 'You are not allowed to follow yourself'
            ]);

        }else{
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();

            if ($follow) {
                if ($follow->is_accepted == false) {
                    $status = 'requested';
                }else{
                    $status = 'following';
                }
                return response()->json([
                    'message' => 'You are already followed',
                    'status' => $status // or 'requested'
                ]);
            }else{
                Follow::create([
                    'follower_id' => Auth::user()->id,
                    'following_id' => $user->id
                ]);
            }
    
        }

        
    }
}
