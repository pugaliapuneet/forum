<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Trending;

class TrendingThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->trending = new Trending();

        $this->trending->reset();
    }

    /** @test */
    public function it_increments_a_thread_score_each_time_it_is_read()
    {
        $this->assertEmpty($this->trending->get());

        $thread = create('App\Thread');
        
        $this->call('GET', $thread->path());

        $this->assertCount(1, $trending = $this->trending->get());
        
        $this->assertEquals($thread->title, $trending[0]->title);
    }
}
