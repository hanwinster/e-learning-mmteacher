<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ResourcesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_can_browse_resources()
    {
        $this->get('/resources')->assertStatus(200);
    }
}
