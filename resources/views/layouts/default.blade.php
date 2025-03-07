<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'TrailerSky | Movie & TV Show Trailers')</title>

    @include('layouts.parts.meta')

    @include('layouts.parts.scripts')

    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    @stack('scripts')
</head>

<body class="@yield('body-class', '')">
    @yield('before_header')
    @include('layouts.parts.header')
    @yield('content')
    @include('layouts.parts.footer')
    @yield('after_footer')
</body>

</html>
