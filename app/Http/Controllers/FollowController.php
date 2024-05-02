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
            ]);
        //jika tidak ketemu data akun yang mau di follow, lanjut cek username diri sendiri berdasarkan data login
        }elseif(Auth::user()->username == $username){
            return response()->json([
                'message' => 'You are not allowed to follow yourself'
            ]);
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
                ]);
            
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
                ]);
            }
    
        }

        
    }
}
