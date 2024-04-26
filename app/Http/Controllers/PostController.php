<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PostAttachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(),[
            'caption'   => 'required',
            'attachment'=> 'required|image|mimes:jpg,jpeg,webp,png,gif',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'message'   => 'Invalid Field',
                'errors'  => $validated->errors()
            ], 422);
        }

        if ($request->hasFile('attachment')) {
            $strRandom = Str::random(40);
            $extension = $request->file('attachment')->extension();
            $newImageName = $strRandom.'.'.$extension;
            
            Storage::putFileAs('images', $request->file('attachment'),  $newImageName);
            $request['storage_path'] = $newImageName;
        }

        $request['user_id'] = Auth::user()->id;
        $post = Post::create($request->all());
        $postAttachment = PostAttachment::create([
            'storage_path' => $request['storage_path'],
            'post_id' => $post->id
        ]);

        return response()->json([
            'message'   => 'Create post success'
        ], 201);        
    }

    public function destroy(){
        
    }
}
