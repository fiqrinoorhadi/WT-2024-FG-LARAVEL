<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function users()
    {
        $loggedInUserId = Auth::user()->id;

        $usersNotFollowedByLoggedInUser = User::leftJoin('follow', function($join) use ($loggedInUserId) {
            $join->on('users.id', '=', 'follow.following_id')->where('follow.follower_id', '=', Auth::id());
        })->whereNull('follow.id') // Filter out followed users
          ->where('users.id', '!=', $loggedInUserId) // Exclude the logged-in user
          ->select('users.*')
          ->get();

        return response()->json($usersNotFollowedByLoggedInUser);
    }
    public function userDetail($username)
    {
        $userDetail = User::where('username', $username)->first();

        if ($userDetail->id != Auth::user()->id) {
            $is_your_account = false;
        }else{
            $is_your_account = true;
        }

        $transformedUserDetail = [
                'id' => $userDetail->id,
                'full_name' => $userDetail->full_name,
                'username' => $userDetail->username,
                'bio' => $userDetail->bio,
                'is_private' => $userDetail->is_private,
                'created_at' => $userDetail->created_at,
                'is_your_account' => $is_your_account,

                'post_count'    => $userDetail->post->count(),
                
            ];
        ;

        return response()->json($transformedUserDetail);
    }
}
