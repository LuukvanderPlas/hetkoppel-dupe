@props(['template'])

<div>
    <label for="album" class="block text-sm font-bold text-gray-700 mb-2">Album</label>
    <select id="album" name="album_id" class="input-field border rounded-md px-4 py-2 w-full">
        <option value="">Selecteer een album</option>
        @foreach ($albums as $album)
            <option value="{{ $album->id }}" {{ $template->pivot->data->album_id == $album->id ? 'selected' : '' }}>
                {{ $album->name }}
            </option>
        @endforeach
    </select>

    <label for="preview_text" class="block text-sm font-bold text-gray-700 mb-2">Preview tekst</label>
    <textarea name="preview_text" id="preview_text" class="font-mono border rounded py-2 px-3 w-full">{{ old('preview_text', $template->pivot->data->preview_text) }}</textarea>

    <p class="text-sm text-gray-500 mt-1">
        Selecteer een album om te tonen op de pagina. De template laat de eerste foto, titel, datum en beschrijving van het album zien.
    </p>
</div>
