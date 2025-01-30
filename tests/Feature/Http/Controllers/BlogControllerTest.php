<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    public mixed $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create(['admin' => true, 'student' => false]);
    }

    public function test_admin_blog_list_view_200()
    {
        $response = $this->actingAs($this->user)->get(route('admin.blog.list'));

        $response->assertOk()
            ->assertViewIs('webapp.admin.blog.index');
    }

    public function test_admin_blog_list_url_200()
    {
        $response = $this->actingAs($this->user)->get('/admin/blog');

        $response->assertOk()
            ->assertViewIs('webapp.admin.blog.index');
    }

    public function test_admin_blog_list_web_url_200()
    {
        $response = $this->actingAs($this->user)->get('/web/blogs');

        $response->assertOk();
    }

    public function test_blog_factory()
    {
        factory(Blog::class)->create();

        $this->assertDatabaseCount('blogs', 1);
    }

    public function test_blog_create_success()
    {
        $this->actingAs($this->user)->post('/web/blog', [
            'author_id' => $this->user->id,
            'title' => 'Test Blog Test',
            'slug' => 'test-blog-test',
            'body' => 'Blog body data here.',
            'image' => null,
            'released_on' => now()->subWeek()->format('Y-m-d'),
            'release_time' => now()->format('H:i:s'),
        ]);

        $blog = Blog::first();

        $this->assertDatabaseCount('blogs', 1);

        $this->assertDatabaseHas('blogs', [
            'author_id' => $this->user->id,
            'title' => $blog->title,
            'slug' => $blog->slug,
            'body' => $blog->body,
            'image' => null,
            'released_on' => $blog->released_on,
        ]);
    }

    public function test_blog_edit()
    {
        $blog = factory(Blog::class)->create();

        $response = $this->actingAs($this->user)->get('/web/blog/' . $blog->id . '/edit');

        $response->assertOk();
        $response->assertJsonCount(10);
    }

    public function test_blog_update_success()
    {
        $blog = factory(Blog::class)->create();

        $response = $this->actingAs($this->user)->put('/web/blog/' . $blog->id, [
            'author_id' => $this->user->id,
            'title' => 'Test Blog Test Update',
            'slug' => 'test-blog-test-update',
            'body' => 'Blog body data updated here.',
            'image' => null,
            'released_on' => now()->subMonths(2)->format('Y-m-d'),
            'release_time' => now()->format('H:i:s'),
        ]);

        $response->assertStatus(201);
    }

    public function test_blog_delete_success()
    {
        $blog = factory(Blog::class)->create();

        $response = $this->actingAs($this->user)->delete('/web/blog/' . $blog->id);

        $response->assertOk();

        $this->assertSoftDeleted('blogs', $blog->toArray());
    }
}
