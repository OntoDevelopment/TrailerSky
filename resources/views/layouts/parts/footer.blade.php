<footer class="bg-dark text-light">
    <div class="container pt-3">
        <div class="row align-items-center">
            <div class="col-md-6 col-lg-3 mb-2">
                &copy; {{ date('Y') }} TrailerSky
            </div>
            <div class="col-md-6 col-lg-6 mb-2 text-md-center">
                This product uses the <a href="{{ route('page', ['about', '#tmdb']) }}" class="fw-bold">TMDB</a> API but is not endorsed or certified by TMDB.
            </div>
            <div class="col-md-6 col-lg-3 mb-2 text-md-end">
                <a href="{{ route('about') }}" class="text-light">About</a> | 
                <a href="{{ route('page', 'privacy') }}" class="text-light">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>