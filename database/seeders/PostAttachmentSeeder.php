<?php

namespace Database\Seeders;

use App\Models\PostAttachment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostAttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PostAttachment::create([
            'storage_path'  => 'gambar1.jpg',
            'post_id'       => 1
        ]);

        PostAttachment::create([
            'storage_path'  => 'gambar2.jpg',
            'post_id'       => 2
        ]);

        PostAttachment::create([
            'storage_path'  => 'gambar3.jpg',
            'post_id'       => 3
        ]);
    }
}
