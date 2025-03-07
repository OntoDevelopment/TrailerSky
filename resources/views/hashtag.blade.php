@extends('layouts.default')

@section('canonical', route('hashtag', $hashtag->text))
@section('title', '#' . $hashtag->text . ' on TrailerSky')

@section('content')
    <div class="container my-4">
        <x-back/>
        <div id="results" class="text-light">
            <h1>#{{ $hashtag->text }}</h1>

            <h2>Movies</h2>
            @if ($videos->where('media.media_type', 'movie')->count())
                
                <div class="thing-list">
                    @foreach ($videos->where('media.media_type', 'movie') as $video)
                        <x-video-list-item :video="$video" />
                    @endforeach
                </div>
            @else
                <p>No movies found for #{{ $hashtag->text }}.</p>
            @endif
            
            <h2>Shows</h2>
            @if ($videos->where('media.media_type', 'tv')->count())
                
                <div class="thing-list">
                    @foreach ($videos->where('media.media_type', 'tv') as $video)
                        <x-video-list-item :video="$video" />
                    @endforeach
                </div>
            @else
                <p>No shows found for #{{ $hashtag->text }}.</p>
            @endif
        </div>
    </div>
@endsection
