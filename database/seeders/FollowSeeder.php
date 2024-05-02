<?php

namespace Database\Seeders;

use App\Models\Follow;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FollowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Follow::create([
            'follower_id'   => 1,
            'following_id'   => 2,
            'is_accepted'   => true
        ]);
        Follow::create([
            'follower_id'   => 1,
            'following_id'   => 3
        ]);
        Follow::create([
            'follower_id'   => 2,
            'following_id'   => 1
        ]);
        Follow::create([
            'follower_id'   => 2,
            'following_id'   => 3
        ]);
        Follow::create([
            'follower_id'   => 3,
            'following_id'   => 1
        ]);
        Follow::create([
            'follower_id'   => 3,
            'following_id'   => 2
        ]);
    }
}
