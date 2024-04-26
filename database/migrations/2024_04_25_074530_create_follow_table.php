<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('follow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id');
            $table->foreign('follower_id')->references('id')->on('users');
            $table->unsignedBigInteger('following_id');
            $table->foreign('following_id')->references('id')->on('users');
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('follow');
    }
};
