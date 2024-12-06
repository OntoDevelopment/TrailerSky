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
        </div>
        <iframe id="run" name="run" src="{{ route('admin.runplaceholder') }}" class="border w-100"></iframe>

        @includeWhen($to_review->count(), 'admin.dashboard.to_review')

        @includeWhen($to_post->count(), 'admin.dashboard.to_post')

        {{ implode(', ', array_keys(\App\Http\YouTube\Channels::$list)) }}
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