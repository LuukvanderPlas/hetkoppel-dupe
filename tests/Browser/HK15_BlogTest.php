<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HK15_BlogTest extends DuskTestCase
{
    private $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('administrator');
    }

    private function createBlog(Browser $browser): void
    {
        $browser->loginAs($this->adminUser)
            ->visit(route('post.create'))
            ->type('title', 'test')
            ->type('slug', 'test')
            ->press('Aanmaken');
    }
    /*
    * Test, Een beheerder kan een blog/post maken
    */
    public function testhk15_1CreateBlog(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createBlog($browser);
            $browser->loginAs($this->adminUser)
                ->waitForText('Gelukt!')
                ->assertSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder kan een blog/post bewerken
    */
    public function testhk15_2EditBlog(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createBlog($browser);
            $browser->loginAs($this->adminUser)
                ->clickLink('Wijzigen')
                ->type('title', 'bewerkt')
                ->press('Bijwerken')
                ->assertsee('Gelukt!')
                ->assertsee('bewerkt');
        });
    }
    /*
    * Test, Een beheerder kan een blog/post verwijderen
    */
    public function testhk15_3DeleteBlog(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createBlog($browser);
            $browser->loginAs($this->adminUser)
                ->clickLink('Wijzigen')
                ->press('Verwijderen')
                ->click('#deleteconfirm')
                ->assertsee('Gelukt!')
                ->assertDontsee('test');
        });
    }
    /*
    * Test, Een beheerder krijgt een error wanneer hij een blog zonder titel probeert te maken
    */
    public function testhk15_9IncorrectTitleCreateBlog(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('post.create'))
                ->type('slug', 'test')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder krijgt een error wanneer hij een blog zonder Url/Slug probeert te maken
    */
    public function testhk15_10IncorrectSlugCreateBlog(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createBlog($browser);
            $browser->loginAs($this->adminUser)
                ->clickLink('Wijzigen')
                ->type('slug', '')
                ->press('Bijwerken')
                ->assertDontSee('Gelukt!');
        });
    }
}
