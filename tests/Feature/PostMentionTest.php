<?php

namespace Tests\Feature;

use App\Support\MentionFormatter;
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

    public function test_mentioned_follower_receives_only_one_notification(): void
    {
        $author = $this->createUser('author');
        $mentioned = $this->createUser('mentioned');
        $author->followers()->attach($mentioned->id);

        $this->actingAs($author)->post(route('posts.store'), [
            'titulo' => 'Una sola notificación',
            'descripcion' => 'Hola @mentioned',
            'imagen' => 'mencion-unica.jpg',
        ])->assertRedirect();

        $this->assertSame(1, $mentioned->fresh()->notifications()->count());
        $this->assertSame('post_mention', $mentioned->fresh()->notifications()->first()->data['kind']);
    }

    public function test_existing_mentions_are_rendered_as_safe_profile_links(): void
    {
        $mentioned = $this->createUser('mentioned');

        $html = MentionFormatter::toHtml('Hola @mentioned <script>alert(1)</script>')->toHtml();

        $this->assertStringContainsString(route('posts.index', $mentioned), $html);
        $this->assertStringContainsString('@mentioned</a>', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }

    private function createUser(string $username): User
    {
        return User::factory()->create([
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
