<?php

namespace Tests\Feature;

use App\Mail\WelcomeUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationMailTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_creates_the_user_and_sends_the_welcome_message(): void
    {
        Mail::fake();

        $this->post(route('register'), [
            'name' => 'Usuario Nuevo',
            'username' => 'usuario.nuevo',
            'email' => 'nuevo@devstagram.test',
            'password' => 'secreto123',
            'password_confirmation' => 'secreto123',
        ])->assertRedirect(route('welcome'));

        $this->assertDatabaseHas('users', [
            'username' => 'usuario-nuevo',
            'email' => 'nuevo@devstagram.test',
        ]);

        Mail::assertSent(WelcomeUser::class, function (WelcomeUser $mail) {
            return $mail->hasTo('nuevo@devstagram.test');
        });
    }
}
