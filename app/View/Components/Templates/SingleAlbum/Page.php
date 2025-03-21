<?php

namespace App\View\Components\Templates\SingleAlbum;

use Closure;
use App\Models\Album;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Page extends Component
{
    public ?Album $album;
    public $preview_text;

    /**
     * Create a new component instance.
     */

    public function __construct($template)
    {
        $this->album = Album::active()->find($template->pivot->data->album_id);
        $this->preview_text = $template->pivot->data->preview_text;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.templates.single-album.page');
    }
}
