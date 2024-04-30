<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $table = "follow";
    protected $guarded = ['id'];
    public $timestamps = false;

    public function userFollower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function userFollowing()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
