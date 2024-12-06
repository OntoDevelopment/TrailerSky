@foreach ($Action->log as $log)
    <pre class="{{ $log->error ? 'alert alert-error' : '' }}">{!! $log->message !!}</pre>
@endforeach
