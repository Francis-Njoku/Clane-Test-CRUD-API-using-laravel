<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Article;
use Faker\Generator as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArtTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        Event::fake();

    }
    /** @test */
    public function only_authors_can_post()
    {
       $this->actingAs(factory(User::class)->create());
        $response = $this->get('/article/create')
            ->assertOk();
    }

    /**
     * Test User Post
     */

    /** @test */
    public function a_user_cannot_post_without_authentication()
    {
        $response = $this->json('POST', '/article', ['title' => 'Sally']);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status_code' => 401,
            ]);
    }

    /** @test */
    public function test_that_only_authorized_users_can_manage_a_post()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();

        // Guests
        //$this->get('/article/create')->assertRedirect('login');

        // Users
        $this->actingAs($user)->get('/article/create')->assertStatus(200);
    }

    /** @test */
    public function a_post_can_be_added()
    {
        $this->withExceptionHandling();

        $this->actingAs(factory(User::class)->create());

        $response = $this->post('/article', [
            'title' => 'test title',
            'post' => 'postes',
            'author' => '9'
        ]);

        $this->assertCount(1, Article::all());
    }

}
