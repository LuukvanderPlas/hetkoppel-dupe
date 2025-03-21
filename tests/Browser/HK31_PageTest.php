<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HK31_PageTest extends DuskTestCase
{
    private $adminUser;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('administrator');
    }

    private function createPage(Browser $browser): void
    {
        $browser->loginAs($this->adminUser)
            ->visit(route('page.create'))
            ->type('title', 'test')
            ->type('slug', 'test')
            ->press('Aanmaken');
    }
    /*
    * Test, Een beheerder probeert een pagina aan te maken
    */
    public function testhk31_1CreatePage(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createPage($browser);
            $browser->assertSee('Gelukt!')
                ->assertSee('test');
        });
    }
    /*
    * Test, Een beheerder probeert een pagina te vewerken
    */
    public function testhk31_2EditPage(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createPage($browser);
            $browser->type('title', 'bewerkt')
                ->press('Bijwerken')
                ->assertsee('Gelukt!')
                ->assertSee('bewerkt');
        });
    }
    /*
    * Test, Een beheerder probeert een pagina aan te verwijderen
    */
    public function testhk31_3DeletePage(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createPage($browser);
            $browser->click('.text-red-600.hover\\:text-red-900.cursor-pointer')
                ->click('#deleteconfirm')
                ->assertsee('Gelukt!')
                ->assertDontSee('test');
        });
    }
    /*
    * Test, Een beheerder probeert een pagina zonder titel te maken
    */
    public function testhk31_11IncorrectTitleCreatePage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('page.create'))
                ->type('slug', 'test')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder probeert een pagina zonder Url/Slug te maken
    */
    public function testhk31_12IncorrectSlugCreatePage(): void
    {
        $this->browse(function (Browser $browser) {
            $this->createPage($browser);
            $browser->waitUntilMissingText('Gelukt!')
                ->type('slug', '')
                ->press('Bijwerken')
                ->assertDontSee('Gelukt!');
        });
    }
}
