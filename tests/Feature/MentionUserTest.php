<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notified()
    {
        $this->withoutExceptionHandling();
        //Given I have a user1 who is signed in
        $john = create('App\User', ['name' => 'JohnDoe']);
        
        $this->signIn($john);
        
        //And another user2 
        $jane = create('App\User', ['name' => 'JaneDoe']);

        //if we have a thread
        $thread = create('App\Thread');

        //and user1 mentions @user2 in a reply
        $reply = make('App\Reply', [
            'user_id' => $john->id, 
            'body' => "Hey @JaneDoe how are you doing? @FrankDoe",
        ]);

        $response = $this->post($thread->path().'/replies', $reply->toArray());

        //then user2 should be notified.
        $this->assertCount(1, $jane->notifications);
    }

    /** @test */
    function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
    {
        create('App\User', ['name' => 'johndoe']);
        create('App\User', ['name' => 'johndoe2']);
        create('App\User', ['name' => 'janedoe']);
        
        $response = $this->json('GET', '/api/users', ['name' => 'john']);

        $this->assertCount(2, $response->json());

        $response = $this->json('GET', '/api/users', ['name' => 'j']);

        $this->assertCount(3, $response->json());
    }
}
