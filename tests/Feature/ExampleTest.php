<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_open_the_home_page(): void
    {
        $user = User::factory()->create([
            'username' => 'usuario-prueba',
            'email' => 'usuario-prueba@devstagram.test',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertOk();
    }
}
