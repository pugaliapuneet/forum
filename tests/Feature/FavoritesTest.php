<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function guests_cannot_favorite_anything()
    {
        $response = $this->post("replies/1/favorites")
            ->assertRedirect('/login');
    }

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        $response = $this->post("replies/{$reply->id}/favorites");
        
        $this->assertCount(1, $reply->favorites);
    }
    
    /** @test */
    public function an_authenticated_user_can_unfavorite_any_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $reply = create('App\Reply');

        $reply->favorite();
        
        $response = $this->delete("replies/{$reply->id}/favorites");
        
        $this->assertCount(0, $reply->favorites);
    }
    
    /** @test */
    public function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->signIn();

        $reply = create('App\Reply');

        try {
            $response = $this->post("replies/{$reply->id}/favorites");
            $response = $this->withoutExceptionHandling()->post("replies/{$reply->id}/favorites");
        } catch (\Exception $e) {
            $this->fail('Did not except to insert the same record set twice.');
        }
        
        $this->assertCount(1, $reply->favorites);
    }
}
