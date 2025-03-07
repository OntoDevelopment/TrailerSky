<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">

@hasSection('keywords')
    <meta name="keywords" content="@yield('keywords')" />
@endif

@hasSection('canonical')
    <link rel="canonical" href="@yield('canonical')" />
@endif

@hasSection('description')
    <meta name="description" content="@yield('description')" />
    <meta property="og:description" content="@yield('description')" />
@endif

<meta property="og:type" content="website" />
<meta property="og:image" content="@yield('image', '/img/share.jpg')" />