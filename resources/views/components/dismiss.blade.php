@props(['id'])
<a href="{{ route('admin.util.dismiss', ['id' => $id]) }}" class="btn btn-danger" target="run" onclick="return confirm('Are you sure?')">
    {{ $slot ?? 'Dismiss' }}
</a>