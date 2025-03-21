<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class HK138_TrashTest extends DuskTestCase
{
    private $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('administrator');
    }

    public function testhk138_1PermDeleteOnPost(): void
    {
        $post = Post::create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'isActive' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->adminUser)
                ->visit(route('post.edit', $post->id))
                ->click('.delete')
                ->click('#deleteconfirm')
                ->visit(route('trash.index'))
                ->click('.Post')
                ->click('.delete')
                ->click('.Post')
                ->assertDontSee($post->title);
        });
    }

    public function testhk138_2RestoreOnPost(): void
    {
        $post = Post::create([
            'title' => 'Test Post',
            'slug' => 'test-post',
            'isActive' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($post) {
            $browser->loginAs($this->adminUser)
                ->visit(route('post.edit', $post->id))
                ->click('.delete')
                ->click('#deleteconfirm')
                ->visit(route('trash.index'))
                ->click('.Post')
                ->click('.restore')
                ->visit(route('post.index'))
                ->assertSee($post->title);
        });
    }
}
