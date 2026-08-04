<?php

namespace Tests\Feature;

use App\Http\Livewire\LikePost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SocialInteractionTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_like_or_follow_users(): void
    {
        $author = $this->createUser('author');
        $post = Post::factory()->create(['user_id' => $author->id]);

        $this->post(route('posts.likes.store', $post))
            ->assertRedirect(route('login'));

        $this->post(route('users.follow', $author))
            ->assertRedirect(route('login'));
    }

    public function test_following_the_same_user_twice_does_not_create_duplicates(): void
    {
        $author = $this->createUser('author');
        $follower = $this->createUser('follower');

        $this->actingAs($follower)->post(route('users.follow', $author));
        $this->actingAs($follower)->post(route('users.follow', $author));

        $this->assertDatabaseCount('followers', 1);
    }

    public function test_removing_a_like_keeps_other_users_likes(): void
    {
        $author = $this->createUser('author');
        $firstUser = $this->createUser('first');
        $secondUser = $this->createUser('second');
        $post = Post::factory()->create(['user_id' => $author->id]);

        $post->likes()->create(['user_id' => $firstUser->id]);
        $post->likes()->create(['user_id' => $secondUser->id]);

        Livewire::actingAs($firstUser)
            ->test(LikePost::class, ['post' => $post])
            ->call('like')
            ->assertSet('likes', 1)
            ->assertSet('isLiked', false);

        $this->assertDatabaseMissing('likes', [
            'post_id' => $post->id,
            'user_id' => $firstUser->id,
        ]);
        $this->assertDatabaseHas('likes', [
            'post_id' => $post->id,
            'user_id' => $secondUser->id,
        ]);
    }

    private function createUser(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
