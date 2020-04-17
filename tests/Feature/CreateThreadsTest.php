<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function an_authentication_user_can_create_new_forum_threads()
    {
        $this->signIn();

        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());

        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
    
    /** @test */
    function guests_may_not_create_threads()
    {
        $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = make('App\Thread');
        $this->withoutExceptionHandling()->post('/threads', $thread->toArray());
    }
    
    /** @test */
    function guests_cannot_see_the_create_thread_page()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    
}
