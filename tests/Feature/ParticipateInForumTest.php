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
        // $this->withoutExceptionHandling();

        $this->be($user = create('App\User'));
        $thread = create('App\Thread');

        $reply = make('App\Reply');
        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertDatabaseHas('replies', ['body' => $reply->body]);
        $this->assertEquals(1, $thread->fresh()->replies_count);
    }
    
    /** @test */
    function unauthenticated_users_may_not_add_replies()  
    {
        $this->post('threads/some-channel/1/replies', [])
            ->assertRedirect('/login');
    }
    
    
    /** @test */
    function unauthorized_users_cannot_delete_replies()  
    {
        // $this->withoutExceptionHandling();

        $reply = create('App\Reply');

        $this->delete("/replies/{$reply->id}")
            ->assertRedirect('/login');
        
        $this->signIn()->delete("/replies/{$reply->id}")
            ->assertStatus(403);
    }
    
    /** @test */
    function authorized_users_can_delete_replies()  
    {
        // $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        
        $this->delete("/replies/{$reply->id}")
            ->assertStatus(302);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
        $this->assertEquals(0, $reply->thread->fresh()->replies_count);
    }
    
    /** @test */
    function authorized_users_can_update_replies()  
    {
        // $this->withoutExceptionHandling();
        $this->signIn();
        $reply = create('App\Reply', ['user_id' => auth()->id()]);
        
        $string = 'You have been changed, fool.';
        $this->patch("/replies/{$reply->id}", ['body' => $string]);

        $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $string]);
    }
    
    /** @test */
    function unauthorized_users_cannot_update_replies()  
    {
        $reply = create('App\Reply');

        $this->patch("/replies/{$reply->id}")
            ->assertRedirect('login');

        $this->signIn()->patch("/replies/{$reply->id}")
            ->assertStatus(403);
    }


}
