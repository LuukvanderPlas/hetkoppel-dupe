<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Media;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HK28_SponsorTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     */
    private $adminUser, $media;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('administrator');

        $this->media = new Media([
            'filename' => 'ace.png',
            'alt_text' => 'Media image alt text',
            'file_path' => storage_path('app/testing/medialibrary/ace.png'),
        ]);
        $this->media->css_selector = 'img[alt="' . $this->media->alt_text . '"]';
    }

    private function addImage(Browser $browser): void
    {
        $browser->loginAs($this->adminUser)
            ->visit(route('media.create'))
            ->attach('input#media', $this->media->file_path)
            ->type('input#alt', $this->media->alt_text)
            ->press('button#upload')
            ->waitFor('img[alt="' . $this->media->alt_text . '"]');
    }

    private function CreateSponsor(Browser $browser): void
    {
        $browser->visit(route('sponsors.index'))
            ->clickLink('Nieuwe sponsor')
            ->type('title', 'test')
            ->type('slug', 'test')
            ->press('Kies media')
            ->assertPresent($this->media->css_selector)
            ->click($this->media->css_selector)
            ->press('Aanmaken');
    }

    /*
    * Test, Een beheerder probeert een sponsor aan te maken
    */
    public function testhk28_1CreateSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $this->addImage($browser);
            $this->CreateSponsor($browser);
            $browser->assertSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder probeert een sponsor te bewerken
    */
    public function testhk28_2EditSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $this->addImage($browser);
            $this->CreateSponsor($browser);
            $browser->type('title', 'bewerkt')
                ->press('Bijwerken')
                ->assertsee('bewerkt');
        });
    }
    /*
    * Test, Een beheerder probeert een sponsor te verwijderen
    */
    public function testhk28_3DeleteSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $this->addImage($browser);
            $this->CreateSponsor($browser);
            $browser->press('Verwijderen')
                ->click('#deleteconfirm')
                ->assertDontSee('test');
        });
    }
    /*
    * Test, Een beheerder probeert een sponsor aan te maken met geen titel en krijgt een error
    */
    public function testhk28_9IncorrectTitleCreateSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $this->addImage($browser);
            $browser->visit(route('sponsors.index'))
                ->clickLink('Nieuwe sponsor')
                ->type('slug', 'test')
                ->press('Kies media')
                ->assertPresent($this->media->css_selector)
                ->click($this->media->css_selector)
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder probeert een sponsor aan te maken met geen Url/Slug en krijgt een error
    */
    public function testhk28_10IncorrectSlugCreateSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $this->addImage($browser);
            $browser->visit(route('sponsors.index'))
                ->clickLink('Nieuwe sponsor')
                ->type('title', 'test')
                ->press('Kies media')
                ->assertPresent($this->media->css_selector)
                ->click($this->media->css_selector)
                ->type('slug', '')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /*
    * Test, Een beheerder probeert een sponsor aan te maken met geen afbeelding en krijgt een error
    */
    public function testhk28_11IncorrectImageCreateSponsor(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser);
            $browser->visit(route('sponsors.index'))
                ->clickLink('Nieuwe sponsor')
                ->type('title', 'test')
                ->type('slug', 'test')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
}
