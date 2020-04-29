<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ThreadWasUpdated;
use Carbon\Carbon;

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
    function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
    {
        $this->withoutExceptionHandling();

        Notification::fake();

        $this->signIn()
            ->thread
            ->subscribe()
            ->addReply([
                'body' => 'Foobar', 
                'user_id' => 999,
            ]);

        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
    }

    /** @test */
    function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');

        $this->assertInstanceOf('App\Channel', $thread->channel);
    }

    /** @test */
    public function a_thread_has_a_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->slug}", $thread->path());
    }

    /** @test */
    function a_thread_can_be_subscribed_to() {
        
        //give me a thread
        $thread = create('App\Thread');
        
        //then the user subscribes to the thread
        $thread->subscribe($userId = 1);

        //then we should be able to fetch all threads that the user has subscribed to
        $this->assertEquals(1, $thread->subscriptions()->where('user_id', $userId)->count());
    }
    
    /** @test */
    function a_thread_can_be_unsubscribed_from() {
        
        //give me a thread
        $thread = create('App\Thread');
        
        //then the user who is subscribed to the thread
        $thread->subscribe($userId = 1);

        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }

    /** @test */
    function it_knows_if_the_authenticated_user_is_subscribed_to_it() {
        
        $thread = create('App\Thread');
        $this->signIn();

        $this->assertFalse($thread->isSubscribedTo);
        $thread->subscribe();
        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    public function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
    {
        $this->signIn();

        $thread = create('App\Thread');

        tap(auth()->user(), function($user) use ($thread) {
            
            $this->assertTrue($thread->hasUpdatesFor($user));
            
            $user->readThread($thread);
            
            $this->assertFalse($thread->hasUpdatesFor($user));
        });
    }

    /** @test */
    public function a_threads_body_is_sanitized_automatically()
    {
        $this->withoutExceptionHandling();
        
        $thread = make('App\Thread', ['body' => '<script>alert("bad")</script><p>This is okay.</p>']);

        $this->assertEquals("<p>This is okay.</p>", $thread->body);
    }
}
