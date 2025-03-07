@props(['video'])
<div {{ $attributes->class("thing-list-item thing-list-item-{$video->media->media_type}") }} data-video-id="{{ $video->id }}">
    <a href="{{ route('video', $video) }}" class="thing-list-item-link" title="{{ html_entity_decode($video->title) }}">
        <img src="{{ $video->youtubeThumbnail('mqdefault') }}" alt="" width="320" height="180" class="thing-list-item-thumbnail img-fluid" />
        <div class="thing-list-item-meta">
            <b class="thing-list-item-title">{{ $video->media->title }}</b>
            <i class="thing-list-item-ago">{{ $video->ago() }}</i>
        </div>
    </a>
</div>
