<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->index(['post_id', 'user_id'], 'likes_post_user_index');
        });

        Schema::table('followers', function (Blueprint $table) {
            $table->index(['user_id', 'follower_id'], 'followers_user_follower_index');
        });
    }

    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex('likes_post_user_index');
        });

        Schema::table('followers', function (Blueprint $table) {
            $table->dropIndex('followers_user_follower_index');
        });
    }
};
