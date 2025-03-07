<div {{ $attributes->class('hashtag-list') }} data-scope="{{ $scope }}">
    @foreach ($hashtags as $hashtag)
        <div class="hashtag-list-item">
            <a href="{{ route('hashtag', $hashtag->text) }}">#{{ $hashtag->text }}</a>
        </div>
    @endforeach
</div>
