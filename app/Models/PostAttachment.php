<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostAttachment extends Model
{
    use HasFactory;

    protected $guarder = ['id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
