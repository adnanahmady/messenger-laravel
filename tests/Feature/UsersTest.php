<?php

namespace Tests\Feature;

use App\Mail\UseYourOtpForLoginEmail;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_register_by_email()
    {
        $this->withoutExceptionHandling();
        $unregisteredUser = factory('App\User')->raw();
        $this->postJson(route('register'), [
            'email' => $unregisteredUser['email']
        ])->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => $unregisteredUser['email'],
        ]);
        $this->assertDatabaseHas('users', [
            'otp_at' => Carbon::now(),
        ]);
    }

    /** @test */
    public function can_login_using_otp()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->state('otp')->create();

        $response = $this->postJson(route('verify.otp', [
            'otp' => $user->otp
        ]))->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'otp' => null,
            'token_key' => $user->tokenKey($response->json('data')['token'])
        ]);
    }
}
