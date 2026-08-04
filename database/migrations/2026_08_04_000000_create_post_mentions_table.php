<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const MENTION_PATTERN = '/(?<![A-Za-z0-9._%+\-])@([A-Za-z0-9._\-]{3,20})/u';

    public function up(): void
    {
        Schema::create('post_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['post_id', 'user_id'], 'post_mentions_post_user_unique');
        });

        $users = DB::table('users')
            ->get(['id', 'username'])
            ->mapWithKeys(fn ($user) => [strtolower($user->username) => $user->id]);

        DB::table('posts')
            ->select(['id', 'descripcion'])
            ->orderBy('id')
            ->chunkById(100, function ($posts) use ($users) {
                foreach ($posts as $post) {
                    if (! preg_match_all(self::MENTION_PATTERN, (string) $post->descripcion, $matches)) {
                        continue;
                    }

                    $now = now();
                    $rows = collect($matches[1])
                        ->map(fn ($username) => $users->get(strtolower($username)))
                        ->filter()
                        ->unique()
                        ->map(fn ($userId) => [
                            'post_id' => $post->id,
                            'user_id' => $userId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ])
                        ->values()
                        ->all();

                    if ($rows !== []) {
                        DB::table('post_mentions')->insertOrIgnore($rows);
                    }
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_mentions');
    }
};
