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
        //SELECT tabel users WHERE kolom username = $username dari route api
        $user = User::where('username',$username)->first();

        //jika tidak ada hasil, berarti akun tidak ada sama sekali di facegram
        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
            
        //jika $username yang di akan di follow, sama dengan username akun yg sedang kita login
        }elseif(Auth::user()->username == $username){
            return response()->json([
                'message' => 'You are not allowed to follow yourself'
            ], 422);

        //selain kondisi if diatas, berarti SELECT tabel user WHERE kolom username = $username ada hasil
        }else{

            //lalu SELECT tabel follow WHERE kolom follower_id = Auth::user()->id DAN kolom following_id = $user->id
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();

            // jika ada hasil, berarti kita sudah memfollow akun tersebut
            if ($follow) {

                //lalu buatkan variabel $status berdasarkan kondisi dibawah ini
                if ($follow->is_accepted == false) {
                    $status = 'requested';
                }else{
                    $status = 'following';
                }

                return response()->json([
                    'message' => 'You are already followed',
                    'status' => $status // 'following' | 'requested'
                ], 422);
            
            //jika tidak ketemu datanya di tabel follow, user yg sedang login bisa memfollow $username yg dari route api
            //dan data masuk ke tabel follow
            }else{
                // isi variabel $status berdasarkan kondisi akun nya private atau tidak berdasarkan akun yg mau di follow
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
        //SELECT tabel users WHERE kolom username = $username dari route api
        $user = User::where('username',$username)->first();

        //jika tidak ada hasil, berarti akun tidak ada sama sekali di facegram
        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        
        //selain kondisi if diatas, berarti SELECT tabel users WHERE kolom username = $username ada hasil
        }else{

            //SELECT tabel follow WHERE kolom follower_id = Auth::user()->id dan kolom following_id = $user->id
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();

            //jika tidak ada hasil, berarti akun login kita tidak memfollow $username yang dari route api
            if (! $follow) {
                return response()->json([
                    'message' => 'You are not following the user'
                ], 422);

            //selain kondisi if diatas, kita memfolow $username yang dari route api, dan siap untuk di unfollow atau di hapus
            }else{
                $follow->delete();
                return response()->json([
                    'message' => 'Unfollow success'
                ], 204);
            }
        }
    }

    //menampilkan semua data yang di tabel follow
    public function following()
    {
        //SELECT tabel follow wtuh nama relasi di model follow
        $follows = Follow::with('userFollowing')->get();

        //jika hasilnya kosong, maka
        if ($follows->isEmpty()) {
            return response()->json([
                'message' => 'User not found'
            ], 404);

        //selain kondisi if diatas, berarti datanya ada, lalu tampilkan 
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
    public function accept($username)
    {
        //SELECT tabel users WHERE kolom username =  $username dari api route / data akun yang mau di accept
        $user = User::where('username',$username)->first();

        //jika tidak hasil
        if (! $user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);

        //selain kondisi if diatas, berarti data akun nya ada di tabel user
        }else{
            //cek di tabel follow  follower_id = Auth::user()->id dan following_id = Auth::user()->id ada di tabel follow
            $follow = Follow::where('follower_id', Auth::user()->id)->where('following_id', $user->id)->first();
            if (!$follow) {
                return response()->json([
                    'message' => 'The user is not following you'
                ], 422);
            }elseif($follow->is_accepted == 1){
                return response()->json([
                    'message' => 'Follow request is already accepted'
                ], 422);
            }else{
                $follow->update([
                    'is_accepted' => 1
                ]);

                return response()->json([
                    'message' => 'Follow request accepted'
                ], 200);
            }
        }
        
        
    }
}
