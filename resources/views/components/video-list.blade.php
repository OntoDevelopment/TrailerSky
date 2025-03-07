<div {{ $attributes->class('thing-list') }} data-scope="{{ $scope }}">
    @foreach ($videos as $video)
        <x-video-list-item :video="$video" />
    @endforeach
</div>