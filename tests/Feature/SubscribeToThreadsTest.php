<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_user_can_subscribe_to_threads()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');

        $this->post($thread->path().'/subscriptions');

        $this->assertCount(1, $thread->subscriptions);
    }
    
    /** @test */
    public function a_user_can_unsubscribe_from_a_thread()
    {
        // $this->withoutExceptionHandling();

        $this->signIn();

        $thread = create('App\Thread');
        $thread->subscribe();
        
        $this->delete($thread->path().'/subscriptions');
        $this->assertCount(0, $thread->fresh()->subscriptions);
    }
}