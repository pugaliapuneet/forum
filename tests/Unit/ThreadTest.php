<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp():void
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    
    /** @test */
    function a_thread_has_a_creator()
    {
        $this->assertInstanceOf('App\User', $this->thread->owner);
    }

    /** @test */
    function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar', 
            'user_id' => 1,
        ]);

        $this->assertCount(1, $this->thread->replies);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    function a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
        
    }
}
