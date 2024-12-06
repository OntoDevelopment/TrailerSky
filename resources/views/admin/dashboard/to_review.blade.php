<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th>Actions</th>
        </tr>
    <tbody>
        @foreach ($to_review as $Video)
            <tr>
                <td>
                    {{ $Video->title }} <a href="{{ $Video->youtubeUrl() }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    <br />{{ $Video->channel->name }} at {{ $Video->created_at->format('F jS, Y g:i A') }}
                </td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('admin.util.action', ['fetchYouTubeDetails', 'id' => $Video->id]) }}" class="btn btn-success" target="run">Get Data</a>
                        <x-dismiss id="{{ $Video->id }}">Reject</x-dismiss>
                        <form action="{{ route('admin.util.set_tmdb_id') }}" method="GET" style="display: inline;" target="run">
                            @csrf
                            <input type="hidden" name="id" value="{{ $Video->id }}" />
                            <div class="input-group">
                                <input type="text" class="form-control" name="tmdb_id" placeholder="TMDB ID" required style="width: 100px;" />
                                <button type="submit" class="btn btn-primary">Set</button>
                            </div>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
