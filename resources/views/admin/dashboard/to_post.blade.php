<table class="table table-striped">
    <thead>
        <tr>
            <th>Title</th>
            <th width="50%">Hashtags</th>
            <th>Post</th>
        </tr>
    <tbody>
        @foreach ($to_post as $Video)
            <tr>
                <td>
                    {{ $Video->title }} <a href="{{ $Video->youtubeUrl() }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    <br />{{ $Video->channel->name }} at {{ $Video->created_at->format('F jS, Y g:i A') }}</td>
                <td><textarea class="form-control">{{ $Video->postGenerate(1000) }}</textarea><span class="wc">{{ strlen($Video->postGenerate(1000)) }}</span></td>
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
