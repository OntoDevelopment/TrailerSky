@extends('layouts.default')

@section('canonical', route('shows'))
@section('title', 'Shows on TrailerSky')

@section('content')
    <div class="container my-4 text-light">
        <x-back />
        <h1>Coming Soon</h1>
        <div class="thing-list">
            @foreach ($media_list->where('release_date', '>', now())->sortBy('release_date') as $media)
                <div class="thing-list-item poster-list-item">
                    @if ($media->poster_url)
                        <img src="{{ $media->poster_url }}" alt="{{ $media->title }}" width="342" height="513" class="thing-list-item-poster">
                    @else
                        <div style="height: 513px; background: #eee"></div>
                    @endif
                    <div class="thing-list-item-meta">
                        <b class="thing-list-item-title">{{ $media->title }}</b>
                        <i class="thing-list-item-ago">coming {{ friendly_date($media->release_date) }}</i>
                    </div>
                </div>
            @endforeach
        </div>
        <h1>All Movies</h1>
        <div class="columns">
            @foreach ($media_list as $media)
                <div class="mb-1"><a href="#" class="fw-bold">{{ $media->title }}</a></div>
            @endforeach
        </div>
    </div>
@endsection
