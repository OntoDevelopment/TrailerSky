@props(['route'])
@php
    if (is_current_route($route)) {
        $attributes = $attributes->class('active')->merge(['aria-current' => 'page']);
    }
@endphp
<a {{ $attributes->class('nav-link') }} href="{{ route($route) }}">{{ $slot }}</a>
