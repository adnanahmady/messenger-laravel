<?php

namespace Tests\Unit;

use App\Events\NewMessageDidSend;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function new_messages_can_broadcast()
    {
        $user = factory(User::class)->create();
        event(new NewMessageDidSend($user));

        $this->assertEventIsBroadcasting(NewMessageDidSend::class);
    }
}
