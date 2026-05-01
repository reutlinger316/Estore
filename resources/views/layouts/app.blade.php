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
    <script>
        (function () {
            const savedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.documentElement.dataset.theme = savedTheme || (prefersDark ? 'dark' : 'light');
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-shell">

    @php
        $user = auth()->user();
        $role = $user->role ?? null;
        $fallbackTitle = ucwords(str_replace(['.', '-'], ' ', Route::currentRouteName() ?? 'dashboard'));
        $pageTitle = trim($__env->yieldContent('page_title')) ?: $fallbackTitle;
        $pageSubtitle = trim($__env->yieldContent('page_subtitle')) ?: (auth()->check()
            ? 'Smarter store management.'
            : 'A modern marketplace for buyers, merchants, and storefronts.');
    @endphp

    @auth
        <aside class="sidebar">
            <div>
                <div class="sidebar__top">
                    <a href="{{ route('home') }}" class="brand">
                        <div class="brand__logo">E</div>
                        <div>
                            <h1 class="brand__title">E-Store</h1>
                            <p class="brand__subtitle">Smart Commerce</p>
                        </div>
                    </a>
                </div>

                <div class="sidebar__section">
                    <p class="sidebar__label">Navigation</p>

                    <nav class="sidebar__nav">
                        <a href="{{ route('home') }}" class="sidebar__link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <span>Home</span>
                        </a>

                        @if($role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="sidebar__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><span>Admin Dashboard</span></a>
                            <a href="{{ route('admin.users.index') }}" class="sidebar__link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><span>Manage Users</span></a>
                            <a href="{{ route('admin.reports.index') }}" class="sidebar__link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><span>User Reports</span></a>
                            <a href="{{ route('admin.users.storefronts') }}" class="sidebar__link {{ request()->routeIs('admin.users.storefronts') ? 'active' : '' }}"><span>Storefront Accounts</span></a>
                            <a href="{{ route('admin.marketplace.settings.edit') }}" class="sidebar__link {{ request()->routeIs('admin.marketplace.settings.*') ? 'active' : '' }}"><span>Marketplace Settings</span></a>
                            <a href="{{ route('admin.marketplace.users') }}" class="sidebar__link {{ request()->routeIs('admin.marketplace.users') ? 'active' : '' }}"><span>Marketplace Users</span></a>
                            <a href="{{ route('admin.loyalty-points.index') }}" class="sidebar__link {{ request()->routeIs('admin.loyalty-points.*') ? 'active' : '' }}"><span>Loyalty Points</span></a>
                        @elseif($role === 'merchant')
                            <a href="{{ route('merchant.dashboard') }}" class="sidebar__link {{ request()->routeIs('merchant.dashboard') ? 'active' : '' }}"><span>Merchant Dashboard</span></a>
                            <a href="{{ route('merchant.storefronts.index') }}" class="sidebar__link {{ request()->routeIs('merchant.storefronts.*') ? 'active' : '' }}"><span>Manage Storefronts</span></a>
                            <a href="{{ route('merchant.discounts.index') }}" class="sidebar__link {{ request()->routeIs('merchant.discounts.*') ? 'active' : '' }}"><span>Discounts</span></a>
                            <a href="{{ route('merchant.restock-requests.index') }}" class="sidebar__link {{ request()->routeIs('merchant.restock-requests.*') ? 'active' : '' }}"><span>Restock Requests</span></a>
                            <a href="{{ route('merchant.performance.index') }}" class="sidebar__link {{ request()->routeIs('merchant.performance.*') ? 'active' : '' }}"><span>Performance</span></a>
                            <a href="{{ route('merchant.loyalty-points.index') }}" class="sidebar__link {{ request()->routeIs('merchant.loyalty-points.*') ? 'active' : '' }}"><span>Loyalty Points</span></a>
                        @elseif($role === 'storefront')
                            <a href="{{ route('storefront.dashboard') }}" class="sidebar__link {{ request()->routeIs('storefront.dashboard') ? 'active' : '' }}"><span>Storefront Dashboard</span></a>
                            <a href="{{ route('storefront.branch-requests') }}" class="sidebar__link {{ request()->routeIs('storefront.branch-requests') ? 'active' : '' }}"><span>Branch Requests</span></a>
                            <a href="{{ route('storefront.orders.index') }}" class="sidebar__link {{ request()->routeIs('storefront.orders.*') ? 'active' : '' }}"><span>Orders</span></a>
                        @elseif($role === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="sidebar__link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"><span>Customer Dashboard</span></a>
                            <a href="{{ route('customer.shops.index') }}" class="sidebar__link {{ request()->routeIs('customer.shops.*') ? 'active' : '' }}"><span>Browse Shops</span></a>
                            <a href="{{ route('customer.cart.index') }}" class="sidebar__link {{ request()->routeIs('customer.cart.*') ? 'active' : '' }}"><span>My Cart</span></a>
                            <a href="{{ route('customer.orders.index') }}" class="sidebar__link {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}"><span>My Orders</span></a>
                            <a href="{{ route('customer.loyalty-points.index') }}" class="sidebar__link {{ request()->routeIs('customer.loyalty-points.*') ? 'active' : '' }}"><span>My Loyalty Points</span></a>
                            <a href="{{ route('customer.creditcards.index') }}" class="sidebar__link {{ request()->routeIs('customer.creditcards.*') ? 'active' : '' }}"><span>Credit Cards</span></a>
                            <a href="{{ route('customer.funds.index') }}" class="sidebar__link {{ request()->routeIs('customer.funds.*') ? 'active' : '' }}"><span>Manage Funds</span></a>
                            <a href="{{ route('customer.marketplace.account') }}" class="sidebar__link {{ request()->routeIs('customer.marketplace.account') ? 'active' : '' }}"><span>Marketplace Account</span></a>
                            <a href="{{ route('customer.marketplace.products.index') }}" class="sidebar__link {{ request()->routeIs('customer.marketplace.products.*') ? 'active' : '' }}"><span>Marketplace</span></a>
                        @endif
                    </nav>
                </div>
            </div>

            <div class="sidebar__bottom">
                <div class="sidebar-user">
                    <div class="sidebar-user__avatar">{{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}</div>
                    <div>
                        <p class="sidebar-user__name">{{ $user->name }}</p>
                        <p class="sidebar-user__role">{{ ucfirst($role) }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="sidebar__logout">Logout</button>
                </form>
            </div>
        </aside>
    @endauth

    <div class="app-main {{ auth()->check() ? 'with-sidebar' : 'no-sidebar' }}">
        <header class="topbar">
            <div>
                <h2 class="topbar__title">{{ $pageTitle }}</h2>
                <p class="topbar__subtitle">{{ $pageSubtitle }}</p>
            </div>

            <div class="topbar__actions">
                <button type="button" class="theme-toggle" data-theme-toggle aria-label="Toggle dark mode">
                    <span class="theme-toggle__icon theme-toggle__icon--light">☾</span>
                    <span class="theme-toggle__icon theme-toggle__icon--dark">☀</span>
                </button>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @else
                    <span class="topbar__pill">{{ ucfirst($role) }}</span>
                @endguest
            </div>
        </header>

        <main class="page-content">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

</body>
</html>
