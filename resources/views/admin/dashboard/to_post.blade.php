<h2>Ready To Post</h2>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>TMDB</th>
            <th>Post</th>
        </tr>
    <tbody>
        @foreach ($to_post as $Video)
            <tr>
                <td>
                    {{ $Video->title }}
                    <a href="{{ $Video->youtubeUrl() }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    
                    <br />{{ $Video->channel_name }} at {{ $Video->created_at->format('F jS, Y g:i A') }}</td>
                <td>
                    <a href="{{ $Video->media->tmdb_url }}" target="_blank">TMDB</a> | Popularity: {{ $Video->media->tmdb_popularity }}
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.util.action', ['postBlueSky', 'id' => $Video->id]) }}" class="btn btn-primary" target="run">BlueSky</a>
                        <x-dismiss id="{{ $Video->id }}">Dismiss</x-dismiss>
                    </div>
                    
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
