@extends('layouts.default')

@section('title', 'Search TrailerSky')
@section('canonical', route('search'))

@section('content')
    <div class="container">
        <div id="results" class="text-light">
            @if ($videos->count())
                <h2>Search Results</h2>
                <div class="thing-list">
                    @foreach ($videos as $video)
                        <x-video-list-item :video="$video" />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
