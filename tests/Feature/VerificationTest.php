<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function must_returns_jwt_token()
    {
        $this->withoutExceptionHandling();
        $response = $this->login();

        $this->assertArrayHasKey('token', $response['data']);
    }

    /** @test */
    public function authorized_user_sees_its_information()
    {
        $this->withoutExceptionHandling();
        $this->get(route('chat'), [
            'Authorization' => $this->login()['data']['token']
        ])->assertStatus(200);
    }

    /** @test */
    public function unauthorized_users_with_no_token_must_be_rejected()
    {
        $this->withoutExceptionHandling();
        $this->get(route('chat'))->assertStatus(403);
    }

    /** @test */
    public function unauthorized_users_must_be_rejected()
    {
        $this->withoutExceptionHandling();
        $response = $this->get(route('chat'), [
            'Authorization' => 'some not key'
        ]);
        $response->assertStatus(403);
    }

    /**
     * login user and verifies otp
     *
     * @return array
     */
    protected function login(): array
    {
        $user = factory('App\User')->state('otp')->create();

        return $this
            ->json(
                'post',
                route('verify.otp'),
                ['otp' => $user->otp]
            )->json();
    }
}
