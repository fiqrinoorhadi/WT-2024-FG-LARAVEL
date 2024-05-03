<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow($username)
    {
        //select tabel user where username dari api route / data akun yang mau di follow
        $user = User::where('username',$username)->first();

        //jika tidak ketemu data akun yang mau di follow
        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        //jika tidak ketemu data akun yang mau di follow, lanjut cek username diri sendiri berdasarkan data login
        }elseif(Auth::user()->username == $username){
            return response()->json([
                'message' => 'You are not allowed to follow yourself'
            ], 422);
        //selain kondisi if diatas, berarti data akun yang mau di follow
        }else{
            //jika data akun yang mau di follow 
            //lalu cek data id login kita dengan data id user yang berdasarkan akun yg ingin kita follow sebelumnya, 
            //cek di tabel follow
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();

            // jika ketemu datanya di tabel follow,
            if ($follow) {
                //jika ketemu datanya di tabel follow, lalu buatkan variabel $status berdasarkan kondisi dibawah ini
                if ($follow->is_accepted == false) {
                    $status = 'requested';
                }else{
                    $status = 'following';
                }

                //kembalikan hasilnya dalam bentuk jeson
                return response()->json([
                    'message' => 'You are already followed',
                    'status' => $status // or 'requested'
                ], 422);
            
            //jika tidak ketemu datanya di tabel follow, user yg sedang login bisa follow username yg dari route api
            //dan data masuk ke tabel follow
            }else{
                // isi variabel $status berdasarkan kondisi akun nya privat atau tidak berdasarkan akun yg mau di follow
                if ($user->is_private == true) {
                    $status = 'requested';
                }else{
                    $status = 'following';
                }

                //insert data ke tabel follow
                Follow::create([
                    'follower_id' => Auth::user()->id,
                    'following_id' => $user->id
                ]);

                //kembalikan hasilnya dalam bentuk jeson
                return response()->json([
                    'message' => 'Follow success',
                    'status' => $status // or 'requested'
                ], 200);
            }
    
        }
    }

    public function unfollow($username)
    {
        $user = User::where('username',$username)->first();

        //jika tidak ketemu data akun yang mau di follow
        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }else{
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();

            if (! $follow) {
                return response()->json([
                    'message' => 'You are not following the user'
                ], 422);
            }else{

                $follow->delete();
                return response()->json([
                    'message' => 'Unfollow success'
                ], 204);
            }
        }
    }

    public function index()
    {
        $follows = Follow::with('userFollowing')->where('follower_id', Auth::user()->id)->get();

        if ($follows->isEmpty()) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }else{
            $transformedFollows = $follows->map(function ($follow) {
                return [
                    'id' => $follow->id,
                    'full_name'     => $follow->userFollowing->full_name,
                    'username'      => $follow->userFollowing->username,
                    'bio'           => $follow->userFollowing->bio,
                    'is_private'    => $follow->userFollowing->is_private,
                    'created_at'    => $follow->created_at,
                    'is_requested' => ($follow->is_accepted == 0) ? 1 : 0,
                ];
            });
    
            return response()->json([
                'following' => $transformedFollows
            ], 200);
        }
        
    }
}
