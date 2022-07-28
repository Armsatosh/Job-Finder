<?php

namespace Tests\Unit;



use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_main_route_is_work()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
    public function test_main_page_for_unAuthorize_user()
    {
        $this->withoutExceptionHandling();
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertViewIs('home');
        $response->assertViewHas('jobsList');
    }
    public function test_show_user_created_jobs()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/job');
        $response->assertSuccessful();
        $response->assertViewIs('job.list');
        $response->assertViewHas('jobsList');
    }
}
