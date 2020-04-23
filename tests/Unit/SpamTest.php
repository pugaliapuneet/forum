<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Inspections\Spam;

class SpamTest extends TestCase
{
    /** @test */
    public function it_checks_for_invalid_keywords()
    {
        //invalid keywords
        $spam = new Spam();

        $this->assertFalse($spam->detect('Innocent reply here'));
        $this->expectException('Exception');
        $spam->detect('Yahoo customer support');
    }

    /** @test */
    public function it_checks_for_any_key_being_held_down()
    {
        //key held down
        $spam = new Spam();
        
        $this->expectException('Exception');
        
        $spam->detect('Hello World aaaaa');
    }
}
