<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostMentionTest extends TestCase
{
    use RefreshDatabase;

    public function test_mentioned_user_receives_notification(): void
    {
        $author = $this->createUser('author');
        $mentioned = $this->createUser('mentioned');

        $this->actingAs($author)->post(route('posts.store'), [
            'titulo' => 'Publicación con mención',
            'descripcion' => 'Hola @mentioned, revisa esta publicación.',
            'imagen' => 'mencion.jpg',
        ])->assertRedirect();

        $notification = $mentioned->fresh()->unreadNotifications()->first();

        $this->assertNotNull($notification);
        $this->assertSame('post_mention', $notification->data['kind']);
    }

    private function createUser(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
