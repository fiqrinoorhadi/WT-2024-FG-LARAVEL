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

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json([
            'message'   => 'Delete post success'
        ], 204);
    }

    public function index(Request $request)
    {
        // Validate page and size parameters
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:0',
            'size' => 'nullable|integer|min:1',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Retrieve validated page and size parameters
        $page = $validatedData['page'] ?? 0; // Default to 0 if not provided
        $size = $validatedData['size'] ?? 10; // Default to 10 if not provided

        // Get posts with user and attachments eager loaded
        $posts = Post::with('user', 'postAttachment')
                    ->skip($page * $size) // Offset for pagination
                    ->take($size) // Limit for pagination
                    ->get();

        // Transform the data to match the desired structure
        $transformedPosts = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'caption' => $post->caption,
                'created_at' => $post->created_at,
                'deleted_at' => $post->deleted_at,
                'user' => [
                    'id' => $post->user->id,
                    'full_name' => $post->user->full_name,
                    'username' => $post->user->username,
                    'bio' => $post->user->bio,
                    'is_private' => $post->user->is_private,
                    'created_at' => $post->user->created_at,
                ],
                'attachments' => $post->postAttachment->map(function ($attachment) {
                    return [
                        'id' => $attachment->id,
                        'storage_path' => $attachment->storage_path,
                    ];
                }),
            ];
        });

        // Return the transformed data as JSON response
        return response()->json([
            'page' => $page,
            'size' => $transformedPosts->count(),
            'posts' => $transformedPosts,
        ]);
    }

}
