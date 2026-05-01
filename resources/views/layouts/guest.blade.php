<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Store') }} — Electronics Marketplace</title>
    <meta name="description" content="Premium electronics marketplace for buyers, merchants, and storefronts.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="guest-body">

    <div class="guest-layout">
        <!-- Sidebar Navigation Area with Animation -->
        <div class="guest-sidebar">
            <div class="guest-sidebar-glow"></div>
            
            <a href="{{ route('home') }}" class="guest-sidebar-back">&larr; Back to Home</a>

            <div class="guest-sidebar-content">
                <lottie-player src="{{ asset('animations/Shop.json') }}" background="transparent" speed="1" style="width: 280px; height: 280px;" loop autoplay></lottie-player>
                <h1 class="guest-sidebar__title">Estore</h1>
                <p class="guest-sidebar__subtitle">Premium Electronics Marketplace</p>
            </div>
            
            <div class="guest-sidebar-footer">
                <p>&copy; {{ date('Y') }} Estore. All rights reserved.</p>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="guest-main">
            <div class="guest-bg-shape guest-bg-shape--one"></div>
            <div class="guest-bg-shape guest-bg-shape--two"></div>

            <div class="guest-shell">
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
        </div>
    </div>

</body>
</html>