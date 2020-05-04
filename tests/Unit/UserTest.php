<?php

namespace Tests\Unit;

use App\Mail\UseYourOtpForLoginEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create(
            ['otp' => User::otp(), 'otp_at' => now()]
        );
    }

    /** @test */
    public function with_registration_receives_an_email_containing_otp()
    {
        Mail::fake();

        event(new Registered($this->user));

        Mail::assertSent(UseYourOtpForLoginEmail::class);
    }

    /** @test */
    public function can_make_new_otp()
    {
        $this->assertNotNull(User::otp());
        $this->assertEquals($count = 4, strlen(User::otp($count)));
    }

    /** @test */
    public function user_verification_key_must_get_saved_in_database()
    {
        $this->withoutExceptionHandling();
        $user = factory('App\User')->state('otp')->create();
        $user->verify($token = 'some hashed token');

        (function ($user, $token) {
            $this->assertNull($user->otp);
            $this->assertEquals($token, $user->token_key);
        })($user->fresh(), $token);
    }

    /** @test */
    public function user_can_see_its_send_or_received_messages()
    {
        $user = factory('App\User')->state('authorized')->create();
        factory('App\Message')->create(['sender_id' => $user->id]);
        factory('App\Message')->create(['receiver_id' => $user->id]);

        (function ($user) {
            $this->assertNotNull($user);
            $this->assertArrayHasKey('content', current($user['sends'])['messageable']);
            $this->assertArrayHasKey('content', current($user['receives'])['messageable']);
        })($user->load('sends', 'receives')->toArray());
    }
}
