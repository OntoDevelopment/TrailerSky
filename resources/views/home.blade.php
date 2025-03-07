@extends('layouts.default')

@section('content')
    <div class="container text-light my-4">
        @if ($hot->count() > 0)
            <h2>Hot Right Now</h2>
            <x-video-list scope="hot" class="thing-list-scrolls thing-list-horizontal mb-4" id="hot-right-now" />
        @endif
        <h2>Latest Movie Trailers</h2>
        <x-video-list scope="newestMovies" class="thing-list-scrolls thing-list-horizontal mb-4" id="latest-movies" />

        <h2>Hottest Movie Trailers</h2>
        <x-video-list scope="hottestMovies" class="thing-list-scrolls thing-list-horizontal mb-4" id="hottest-movies" />

        <div class="row justify-content-center">

            <div class="col-xl-4 col-md-6 text-center order-3">
                <h2>Latest Show Trailers</h2>
                <x-video-list scope="newestShows" class="thing-list-scrolls thing-list-vertical mb-4" id="latest-shows" />
            </div>

            <div class="col-xl-4 col-md-6 text-center order-4">
                <h2>Hottest Show Trailers</h2>
                <x-video-list scope="hottestShows" class="thing-list-scrolls thing-list-vertical mb-4" id="hottest-shows" />
            </div>

            <div class="col-xl-4 col-md-12 order-5 order-xl-1">

                <div class="row text-center text-md-start">
                    <div class="col-md-6 col-xl-12 feeds-list mb-4">
                        <h2>BlueSky Feeds</h2>
                        <div class="mb-3">
                            <a href="https://bsky.app/profile/trailersky.com/feed/aaak2t4wplscy" target="_blank" rel="noopener noreferrer" class="h4"><i class="fa-brands fa-bluesky text-bluesky"></i> Movie Trailers</a><br/>
                            <i>(only movie trailers)</i>
                        </div>
                        <div class="mb-3">
                            <a href="https://bsky.app/profile/trailersky.com/feed/aaai5wjxxzslo" target="_blank" rel="noopener noreferrer" class="h4"><i class="fa-brands fa-bluesky text-bluesky"></i> Comedy Trailers</a>
                        </div>
                        <div class="mb-3">
                            <a href="https://bsky.app/profile/trailersky.com/feed/aaai4wks7hlra" target="_blank" rel="noopener noreferrer" class="h4"><i class="fa-brands fa-bluesky text-bluesky"></i> Family Trailers</a>
                        </div>
                        <div class="mb-3">
                            <a href="https://bsky.app/profile/trailersky.com/feed/aaai4voqjmd3i" target="_blank" rel="noopener noreferrer" class="h4"><i class="fa-brands fa-bluesky text-bluesky"></i> Horror Trailers</a>
                        </div>
                        <div class="mb-3">
                            <a href="https://bsky.app/profile/trailersky.com/feed/aaai5fzzktcoq" target="_blank" rel="noopener noreferrer" class="h4"><i class="fa-brands fa-bluesky text-bluesky"></i> Thriller Trailers</a>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-12">
                        <h2>Top Hashtags</h2>
                        <x-hashtag-list scope="top" class="h4"></x-hashtag-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
