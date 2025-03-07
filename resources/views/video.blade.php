@extends('layouts.default')

@section('title', $title . ' | TrailerSky')
@section('canonical', route('video', $video))

@section('content')
    <div class="video-container mb-2">
        <x-video-player :video="$video" autoplay="1" />
    </div>

    <div class="container text-light" style="position: relative; z-index: 100;">
        <h1 class="h4">{{ $video->title }}</h1>
        <p>Uploaded on {{ $video->created_at->format('F j, Y') }}</p>
        <p>{{ $video->description }}</p>
    </div>

    <div class="container my-4 text-light">
        <h2>About <b>{{ ($video->media->title) }}</b></h2>
        <div class="d-flex">
            @if ($video->media->poster_url)
                <img src="{{ $video->media->poster_url }}" alt="{{ $video->media->title }}" width="342" height="513">
            @endif
            <div class="ms-3">
                Learn more at <a href="{{ $video->media->tmdb_url }}" target="_blank">TMDB</a>
            </div>
        </div>
    </div>
@endsection
