<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\Album;
use App\Models\User;

class HK98And139_MediaAlbumTest extends DuskTestCase
{
    private $adminUser, $album;

    public function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('administrator');

        $this->album = Album::factory()->create([
            'name' => 'First album',
            'album_date' => '2024-05-22',
            'slug' => 'first-album',
            'isActive' => true,
        ]);
    }

    private function addImageToAlbum(Browser $browser, $altText = 'firstImage'): void
    {
        $browser->click('#add-media')
            ->assertVisible('.parent.media-chooser:last-of-type')
            ->click('.parent.media-chooser:last-of-type button.open-library-modal')
            ->waitForLivewire()
            ->click('#medialib div.modal-box > div:first-of-type > a')
            ->waitForLivewire()
            ->attach('#medialib input#uploadMedia', storage_path('app/testing/medialibrary/ace.png'))
            ->waitForLivewire()
            ->type('#medialib input#alt', $altText)
            ->waitFor('#medialib button#upload')
            ->press('#medialib button#upload')
            ->waitFor('img[alt="firstImage"]');
    }    

    /**
     * Test, De beheerder voegt een foto toe aan het archief.
     */
    public function testhk98_1AddPictureToAlbum(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]));

            $this->addImageToAlbum($browser);

            $browser->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.edit', [$this->album->id]))
                ->assertVisible('img[alt="firstImage"]');
        });
    }
    public function testhk98_2AddPictureToAlbumMultiUpload(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]))
                ->click('#toggle-option-2')
                ->attach('input#images', storage_path('app/testing/medialibrary/ace.png'))
                ->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.edit', [$this->album->id]))   
                ->assertVisible('img.media-url');
        });
    }

    /**
     * Test, De beheerder verwijderd een foto uit het archief.
     */
    public function testhk98_3RemoveAddedPictureFromAlbum(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]));

            $this->addImageToAlbum($browser);

            $browser->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.edit', [$this->album->id]))
                ->assertVisible('img[alt="firstImage"]')
                ->click('button.remove-button')
                ->waitUntilMissing('img[alt="firstImage"]')
                ->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.edit', [$this->album->id]))
                ->assertDontSee('img[alt="firstImage"]');
        });
    }

    /**
     * Test, Gebruiker navigeert naar de archiefpagina.
     */
    public function testhk98_4AlbumPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]));

            $this->addImageToAlbum($browser);

            $browser->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.show', [$this->album->slug]))
                ->assertVisible('img[alt="firstImage"]');
        });
    }

    /**
     * Test, De beheerder probeert een bestand toe te voegen aan het archief dat geen foto is (bijvoorbeeld een tekstbestand of een uitvoerbaar bestand).
     */
    public function testhk98_5WrongFileType(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]))
                ->click('#add-media')
                ->assertVisible('.media-chooser')
                ->click('button.open-library-modal')
                ->waitForLivewire()
                ->click('#medialib div.modal-box > div:first-of-type > a')
                ->waitForLivewire()
                ->attach('#medialib input#uploadMedia', storage_path('app/testing/medialibrary/test_file.txt'))
                ->waitFor('#medialib button#upload')
                ->press('#medialib button#upload')
                ->waitFor('.text-red-500')
                ->assertSee('Upload media moet een bestand zijn van het bestandstype jpeg, jpg, png, gif, svg, mp4.');
        });
    }
    /**
     * Test, De beheerder probeert een ablum aan te maken zonder titel
     */
    public function testhk98_6IncorrectTitleCreateAlbum(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.create'))
                ->type('slug', 'test')
                ->type('album_date', '12-04-2024')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /**
     * Test, De beheerder probeert een ablum aan te maken Url/Slug titel
     */
    public function testhk98_7IncorrectSlugCreateAlbum(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.create'))
                ->type('name', 'test')
                ->type('album_date', '12-04-2024')
                ->type('slug', '')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }
    /**
     * Test, De beheerder probeert een ablum aan te maken zonder datum
     */
    public function testhk98_8IncorrectDateCreateAlbum(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.create'))
                ->type('name', 'test')
                ->type('slug', 'test')
                ->press('Aanmaken')
                ->assertDontSee('Gelukt!');
        });
    }


    public function testhk139_1AlbumPageSlideshow(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->adminUser)
                ->visit(route('album.edit', [$this->album->id]));

            $this->addImageToAlbum($browser);
            $browser->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.edit', [$this->album->id]));
            $this->addImageToAlbum($browser, 'secondImage');

            $browser->press('form[action="' . route('album.update', ['album' => $this->album]) . '"] button[type="submit"]')
                ->visit(route('album.show', [$this->album->slug]))
                ->assertVisible('img[alt="firstImage"]')
                ->assertVisible('img[alt="secondImage"]')
                ->click('#my-gallery > a:first-of-type')
                ->waitFor('.pswp__scroll-wrap button.pswp__button--arrow--next')
                ->assertSee('1 / 2')
                ->press('.pswp__scroll-wrap button.pswp__button--arrow--next')
                ->waitFor('.pswp__scroll-wrap button.pswp__button--arrow--prev')
                ->assertSee('2 / 2');
        });
    }
}
