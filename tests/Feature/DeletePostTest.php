<?php

namespace Tests\Feature;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_can_delete_a_post_and_its_related_activity(): void
    {
        $owner = $this->createUser('owner');
        $visitor = $this->createUser('visitor');
        $post = Post::factory()->create(['user_id' => $owner->id]);

        Comentario::create([
            'user_id' => $visitor->id,
            'post_id' => $post->id,
            'comentario' => 'Comentario de prueba',
        ]);

        $post->likes()->create([
            'user_id' => $visitor->id,
        ]);

        $response = $this
            ->actingAs($owner)
            ->delete(route('posts.destroy', $post));

        $response
            ->assertRedirect(route('posts.index', $owner->username))
            ->assertSessionHas('mensaje');

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
        $this->assertDatabaseMissing('comentarios', ['post_id' => $post->id]);
        $this->assertDatabaseMissing('likes', ['post_id' => $post->id]);
    }

    public function test_another_user_cannot_delete_the_post(): void
    {
        $owner = $this->createUser('owner');
        $visitor = $this->createUser('visitor');
        $post = Post::factory()->create(['user_id' => $owner->id]);

        $this
            ->actingAs($visitor)
            ->delete(route('posts.destroy', $post))
            ->assertForbidden();

        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    private function createUser(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
