@props(['autoplay' => false, 'video'])
<div class="video-player-wrapper">
    <iframe class="video-player" src="https://www.youtube.com/embed/{{ $video->id }}?autoplay={{ $autoplay ? 1 : 0 }}" title="YouTube video player" frameborder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
</div>
