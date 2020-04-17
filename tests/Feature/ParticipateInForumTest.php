<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    function a_reply_requires_a_body()  
    {
        $this->signIn();
        
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);
        // dd($thread->path().'/replies', $reply->toArray());
        $response = $this->post($thread->path().'/replies', $reply->toArray());
        // dd($response);
        $response->assertSessionHasErrors('body');
    }

    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()  
    {
        $this->be($user = create('App\User'));
        $thread = create('App\Thread');

        $reply = make('App\Reply');
        $this->post($thread->path().'/replies', $reply->toArray());

        $this->get($thread->path())
            ->assertSee($reply->body);
    }
    
    /** @test */
    function unauthenticated_users_may_not_add_replies()  
    {
        $this->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }
}
