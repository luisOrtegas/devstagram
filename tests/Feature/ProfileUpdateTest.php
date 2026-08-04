<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_personal_profile_fields(): void
    {
        $user = User::factory()->create([
            'username' => 'luigi',
            'email' => 'luigi@devstagram.test',
        ]);

        $this->actingAs($user)->post(route('perfil.store'), [
            'name' => 'Luis Ortega',
            'username' => 'luigi',
            'telefono' => '+52 55 1234 5678',
            'direccion' => 'Ciudad de México',
            'biografia' => 'Desarrollador y creador de Devstagram.',
        ])->assertRedirect(route('perfil.index'));

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Luis Ortega',
            'telefono' => '+52 55 1234 5678',
            'direccion' => 'Ciudad de México',
            'biografia' => 'Desarrollador y creador de Devstagram.',
        ]);
    }
}
