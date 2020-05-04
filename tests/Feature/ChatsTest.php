<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class ChatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authorized_user_gets_its_chats()
    {
        $this->withoutExceptionHandling();
        $message = factory('App\Message')->create();

        $response = $this->json('get', route('messages'), [], [
            'Authorization' => 'some.token.'.$message->sender->token_key
        ]);

        $response->assertStatus(200);
        (function ($data, $message) {
            $this->assertNotNull($data);
            $this->assertEquals(
                $message->messageable->content,
                current($data['sends'])['messageable']['content']
            );
        })($response->json('data'), $message);
    }

    /** @test */
    public function authorized_user_gets_its_chat_members()
    {
        $this->withoutExceptionHandling();
        $message = factory('App\Message')->create();

        $response = $this->json('get', route('messages'), [], [
            'Authorization' => 'some.token.'.$message->sender->token_key
        ]);

        $response->assertStatus(200);
        (function ($data) {
            $this->assertNotNull($data);
        })($response->json('data'));
    }

    /** @test */
    public function chats_api_must_return_users_chat_information()
    {
        $user = factory('App\User')->states('otp', 'authorized')->create();
        $sendContents = factory('App\Message', 3)->create(['sender_id' => $user->id]);
        $receiveContents = factory('App\Message', 3)->create(['receiver_id' => $user->id]);
        $nonContent = factory('App\Message', 3)->create();


        $response = $this->json('GET', route('messages'), [], [
            'Authorization' => 'some.token.'.$user->token_key
        ]);

        (function ($type) use (
            $user, $sendContents, $receiveContents, $nonContent
        ) {
            $this->assertEquals($user->name, $type['name']);
            $receivers = $sendContents->pluck('receiver_id')->toArray();
            $senders = $receiveContents->pluck('sender_id')->toArray();
            $others['senders'] = $nonContent->pluck('sender_id')->toArray();
            $others['receivers'] = $nonContent->pluck('receiver_id')->toArray();

            array_map(function ($item) use ($receivers) {
                $this->assertTrue(in_array($item['receiver_id'], $receivers));
                $this->assertNotEmpty($item['messageable_type']);
            }, $type['sends']);

            array_map(function ($item) use ($senders) {
                $this->assertTrue(in_array($item['sender_id'], $senders));
                $this->assertNotEmpty($item['messageable_type']);
            }, $type['receives']);

            array_map(function ($item) use ($others) {
                $this->assertFalse(in_array($item['sender_id'], $others['senders']));
                $this->assertFalse(in_array($item['receiver_id'], $others['receivers']));
            }, $type['sends']);

            array_map(function ($item) use ($others) {
                $this->assertFalse(in_array($item['sender_id'], $others['senders']));
                $this->assertFalse(in_array($item['receiver_id'], $others['receivers']));
            }, $type['receives']);
        })($response->json('data'));
    }
}
