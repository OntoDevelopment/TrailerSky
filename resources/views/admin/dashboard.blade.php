@extends('admin.layout')

@section('view')
    <div class="container my-4">
        <div class="row">
            <div class="col-12">
                <h1>Dashboard</h1>
                <p>Welcome to the admin dashboard.</p>
            </div>
        </div>
        <div class="btn-group">
            <a href="{{ route('admin.util.action', ['scrapeYouTubeChannels']) }}" class="btn btn-primary" target="run">Scrape Channels</a>
            <a href="{{ route('admin.util.action', 'fetchYouTubeDetails') }}" class="btn btn-info" target="run">Get Video Details</a>
            <a href="{{ route('admin.util.action', 'updateMedia') }}" class="btn btn-secondary" target="run">Sync Media to TMDB</a>
            <a href="{{ route('admin.logs.notifications') }}" class="btn btn-danger" target="run">Notifications</a>
        </div>
        <iframe id="run" name="run" src="{{ route('admin.runplaceholder') }}" class="resizable-vertical border w-100"></iframe>
        <form method="get" action="{{ route('admin.util.action', ['importYouTube']) }}" target="run" class="my-3 p-3 border">
            <div class="input-group">
                <input type="text" name="id" class="form-control" placeholder="Import YouTube video by ID" required>
                <button type="submit" class="btn btn-primary">Import Video</button>
            </div>

        </form>

        @includeWhen($to_review->count(), 'admin.dashboard.to_review')

        @includeWhen($to_post->count(), 'admin.dashboard.to_post')

        <div class="row">
            <div class="col-12">
                <div class="d-flex gap-3 mb-2">
                    <a href="{{ route('admin.util.action', ['subscribe']) }}" class="btn btn-primary" target="run">Subscribe Channels</a>
                </div>
                @foreach (\App\Http\YouTube\Channels::$list as $name => $channel)
                    <b>{{ $name }}</b>
                    <div class="d-flex gap-3 mb-2">
                        <a href="{{ route('admin.util.action', ['scrapeYouTubeChannels', 'id' => $channel::$id]) }}" target="run" class="btn btn-success">Scrape</a>

                        <form method="POST" target="run" action="https://pubsubhubbub.appspot.com/subscribe">
                            <input type="hidden" name="hub.callback" value="https://trailersky.com/subscriber/notify">
                            <input type="hidden" name="hub.topic" value="https://www.youtube.com/feeds/videos.xml?channel_id={{ $channel::$id }}">
                            <input type="hidden" name="hub.verify" value="sync">
                            <input type="hidden" name="hub.mode" value="subscribe">
                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>

                        <form method="GET" target="run" action="https://pubsubhubbub.appspot.com/subscription-details">
                            <input type="hidden" name="hub.callback" value="https://trailersky.com/subscriber/notify">
                            <input type="hidden" name="hub.topic" value="https://www.youtube.com/feeds/videos.xml?channel_id={{ $channel::$id }}">
                            <input type="hidden" name="hub.verify" value="sync">
                            <input type="hidden" name="hub.mode" value="subscribe">
                            <button type="submit" class="btn btn-info">Check Sub</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <style>
        td {
            position: relative;
        }

        .wc {
            position: absolute;
            bottom: 0;
            right: 0;
            background: white;
        }
    </style>
@endpush
