<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_profiles_are_hidden_until_a_search_is_made(): void
    {
        $viewer = $this->createUser('viewer', 'Usuario Actual');
        $this->createUser('ana.tech', 'Ana Torres');

        $this->actingAs($viewer)
            ->get(route('users.search'))
            ->assertOk()
            ->assertDontSee('Ana Torres');
    }

    public function test_users_can_search_by_text_and_first_letter(): void
    {
        $viewer = $this->createUser('viewer', 'Usuario Actual');
        $this->createUser('ana.tech', 'Ana Torres');
        $this->createUser('carlos.dev', 'Carlos Mendoza');

        $this->actingAs($viewer)
            ->get(route('users.search', ['q' => 'tech']))
            ->assertOk()
            ->assertSee('Ana Torres')
            ->assertDontSee('Carlos Mendoza');

        $this->actingAs($viewer)
            ->get(route('users.search', ['letra' => 'C']))
            ->assertOk()
            ->assertSee('Carlos Mendoza')
            ->assertDontSee('Ana Torres');
    }

    private function createUser(string $username, string $name): User
    {
        return User::factory()->create([
            'name' => $name,
            'username' => $username,
            'email' => "{$username}@devstagram.test",
        ]);
    }
}
