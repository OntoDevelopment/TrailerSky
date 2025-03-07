@extends('layouts.default')

@section('title', 'About TrailerSky')

@section('content')
    <div class="container my-4 text-light">
        <x-back />
        <div class="mb-4">
            <h1>About TrailerSky</h1>
            <p>Welcome to TrailerSky, your premier destination for the latest and greatest in cinematic previews. We bring you a carefully curated collection of movie trailers from leading film studios, making it easy to
                stay
                up-to-date with the entertainment world's most anticipated releases.</p>

            <p> At TrailerSky, we believe in celebrating the art of storytelling through film. Our platform is designed to keep movie enthusiasts informed and inspired, delivering trailers that spark excitement for the big
                screen.</p>

            <p>For those who like to stay ahead, TrailerSky posts new trailers on BlueSky as soon as they drop, ensuring you're always in the loop. Whether you're a film aficionado or simply looking for your next movie night
                pick, TrailerSky is here to fuel your passion for cinema.</p>

            <p>Explore. Discover. Anticipate. With TrailerSky, the magic of movies is just a click away.</p>
        </div>
        <div class="mb-4">
            <h2>Official YouTube channels we subscribe to:</h2>

            <div class="columns mb-4">
                @foreach ($channels as $channel)
                    <div class="mb-2">
                        <a href="{{ $channel->url }}" target="_blank" rel="noopener noreferrer">
                            {{ $channel->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>



        <div class="row mb-4">
            <div class="col-lg-8 col-md-6">
                <h2 id="tmdb">The Movie Database </h2>
                <p class="fw-bold">This product uses the TMDB API but is not endorsed or certified by TMDB.</p>
                <p>TrailerSky relies on the wonderful contributers at TMDB for release dates, posters, tags, and various other meta data.</p>
            </div>
            <div class="col-lg-4 col-md-6">
                <img src="/img/tmdb.svg" alt="" width="200" height="200">
            </div>
        </div>
    </div>
@endsection
