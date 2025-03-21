@props(['template'])

<div class="hero-page min-h-[95vh] w-100 z-[-10] opacity-0 animate-fadein">
    <x-media :media="MediaHelper::getMedia($template->pivot->data->image)" class="inset-0 w-full h-[95vh] object-cover z-[-99] brightness-[0.55]" />

    <div class="absolute inset-0 flex items-center justify-center flex-col md:mx-24 mx-4">
        <h1 class="text-white text-center text-7xl font-bold mb-5 max-w-full break-words">
            {{ $template->pivot->data->hoofdtekst }}
        </h1>
        <p class="text-gray-400 text-center text-2xl max-w-full break-words">{{ $template->pivot->data->subtekst }}</p>
    </div>
</div>

@push('scripts')
    <script type="module">
        const header = document.querySelector('header');
        const heroPages = document.querySelectorAll('.hero-page');

        if (header && heroPages) {
            heroPages.forEach((heroPage) => {
                header.appendChild(heroPage);
                heroPage.classList.remove('opacity-0');
            });
        }
    </script>
@endpush
