<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{

    public function run(): void
    {
        Post::create([
            'caption'   => 'Postingan milik Fiqri',
            'user_id'   => 1,
        ]);

        Post::create([
            'caption'   => 'Postingan milik Lulu',
            'user_id'   => 2,
        ]);

        Post::create([
            'caption'   => 'Postingan milik raysan',
            'user_id'   => 3,
        ]);
    }
}
