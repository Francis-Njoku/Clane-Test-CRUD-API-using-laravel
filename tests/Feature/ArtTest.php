<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Post;
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
        $response = $this->get('/article')
            ->assertOk();
    }
    /**
     * Test the read resource route.
     */
    public function testRead()
    {
        $post = $this->actingAs(factory(User::class)->create());
        $expected = $this->serialize($post);
        $this->doRead($post)->assertRead($expected);
    }
    /**
     * Test the search route
     */
    public function testSearch()
    {
        // ensure there is at least one model in the database
        $this->actingAs(factory(User::class)->create());
        $this->doSearch([
            'page' => ['number' => 1, 'size' => 10],
        ])->assertSearchedMany();
    }

    /** @test */
    public function testBasicExample()
    {
        $this->actingAs(factory(User::class)->create());
        $this->actingAs(factory(Post::class)->create());
        $response = $this->json('POST', '/article');

        $response
            ->assertStatus(200)
            ->assertJson([
                'status_code' => true,
            ]);
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

        $this->assertCount(1, Post::all());
    }
}
