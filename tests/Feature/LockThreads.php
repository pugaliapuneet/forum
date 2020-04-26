<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LockThreads extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();
        // $this->withoutExceptionHandling();
    }
    
    /** @test */
    public function once_locked_a_thread_may_not_receive_new_replies()
    {
        $this->signIn();

        $thread = create('App\Thread', ['locked' => true]);

        $this->post($thread->path() . '/replies', [
            'body' => 'Foobar', 
            'user_id' => create('App\User')->id,
        ])->assertStatus(422);
    }

    /** @test */
    public function non_admins_may_not_lock_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);

        $this->post(route('locked-threads.store', $thread))
            ->assertStatus(403);

        $this->assertFalse(!! $thread->fresh()->locked);
    }
    
    /** @test */
    public function admins_can_lock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread');

        $this->post(route('locked-threads.store', $thread));

        $this->assertTrue($thread->fresh()->locked);
    }
    
    /** @test */
    public function admins_can_unlock_threads()
    {
        $this->signIn(factory('App\User')->states('administrator')->create());

        $thread = create('App\Thread', ['locked' => true]);
        $this->delete(route('locked-threads.destroy', $thread));

        $this->assertFalse($thread->fresh()->locked);
    }
}
