<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestsMayNotCreateThreads()
    {
        $this->get('/threads/create')
            ->assertRedirect('/login');

        $this->post('/threads')
            ->assertRedirect('/login');
    }

    public function testAuthenticatedUserMustFirstConfirmTheirEmailAddressBeforeCreatingThreads()
    {
        $user = create('App\User', [
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $thread = make('App\Thread');

        return $this->post('/threads', $thread->toArray())
            ->assertStatus(403);
    }

    public function testAnAuthenticatedUserCanCreateNewThreads()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread   = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title);
    }

    public function testAThreadRequiresATitle()
    {
        $this->publishThreads(['title' => null])
            ->assertSessionHasErrors('title');
    }

    public function testAThreadRequiresABody()
    {
        $this->publishThreads(['body' => null])
            ->assertSessionHasErrors('body');
    }

    public function testAThreadRequiresAValidChannel()
    {
        factory('App\Channel', 2)->create();

        $this->publishThreads(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');

        $this->publishThreads(['channel_id' => 999])
            ->assertSessionHasErrors('channel_id');
    }

    public function testUnauthorizedUsersCannotDeleteThreads()
    {
        $thread = create('App\Thread');

        $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);

    }

    public function testAuthorizedUsersCanDeleteThreadsAndCascadeAllReplies()
    {
        $this->withoutExceptionHandling();
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply  = create('App\Reply', ['thread_id' => $thread->id]);

        $this->delete($thread->path())
            ->assertRedirect('/threads');

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertEquals(0, Activity::count());
    }

    public function testAThreadWhoseTitleContainsSpamMayNotBeCreated()
    {
        $this->publishThreads(['title' => 'aaaaaaaaaaaaa'])
            ->assertSessionHasErrors('title');
    }

    public function testAThreadWhoseBodyContainsSpamMayNotBeCreated()
    {
        $this->publishThreads(['body' => 'aaaaaaaaaaaaa'])
            ->assertSessionHasErrors('body');
    }

    public function publishThreads($overrides = [])
    {
        $this->signIn();

        $thread = make('App\Thread', $overrides);

        return $this->post('/threads', $thread->toArray());
    }
}
