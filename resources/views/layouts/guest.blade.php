<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Store') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="guest-body">

    <div class="guest-bg-shape guest-bg-shape--one"></div>
    <div class="guest-bg-shape guest-bg-shape--two"></div>

    <div class="guest-shell">
        <div class="guest-brand">
            <a href="{{ route('home') }}" class="guest-brand__link">
                <div class="brand__logo">E</div>
                <div>
                    <h1 class="guest-brand__title">E-Store</h1>
                    <p class="guest-brand__subtitle">Modern Marketplace Experience</p>
                </div>
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success guest-alert">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger guest-alert">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger guest-alert">
                <ul class="alert-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>