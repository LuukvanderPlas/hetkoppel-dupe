@props(['template'])

@if ($album)
    <a href="{{ route('album.show', $album->slug) }}" aria-label="{{ $album->title }}">
        <div class="flex flex-col md:flex-row cursor-pointer bg-white shadow-md rounded-md overflow-hidden group">
            <div class="w-full md:w-1/3 overflow-hidden flex items-center">
                @if ($albumCount = $album->media->count())
                    <x-media :media="$album->media->first()"
                        class="w-full h-48 object-cover rounded-md transition-transform duration-300 group-hover:scale-105" />
                @endif
            </div>
            <div class="w-full md:w-2/3 p-4 flex flex-col">
                <div class="flex flex-col gap-2 mb-2">
                    <h2 class="text-xl font-bold mb-2">{{ $album->name }}</h2>
                    <p>{{ Carbon::parse($album->album_date)->locale('nl_NL')->isoFormat('D MMMM YYYY') }}</p>

                    @if ($preview_text)
                        <p>{!! nl2br(e($preview_text)) !!}</p>
                    @endif
                </div>
                <p class="mt-auto">
                    <b>Bekijk alle foto's</b>
                </p>
            </div>
        </div>
    </a>
@endif
