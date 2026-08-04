<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_followers_are_notified_when_a_user_publishes(): void
    {
        $author = $this->createUser('author');
        $follower = $this->createUser('follower');

        $author->followers()->attach($follower->id);

        $this->actingAs($author)->post(route('posts.store'), [
            'titulo' => 'Publicación notificada',
            'descripcion' => 'Contenido de prueba',
            'imagen' => 'notificacion.jpg',
        ])->assertRedirect();

        $this->assertSame(1, $follower->fresh()->unreadNotifications()->count());
        $this->assertSame('new_post', $follower->fresh()->unreadNotifications()->first()->data['kind']);
    }

    public function test_post_owner_is_notified_when_another_user_comments(): void
    {
        $owner = $this->createUser('owner');
        $commenter = $this->createUser('commenter');
        $post = Post::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($commenter)->post(route('comentarios.store', [
            'user' => $owner,
            'post' => $post,
        ]), [
            'comentario' => 'Nuevo comentario',
        ])->assertRedirect();

        $this->assertSame(1, $owner->fresh()->unreadNotifications()->count());
        $this->assertSame('new_comment', $owner->fresh()->unreadNotifications()->first()->data['kind']);
    }

    private function createUser(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
