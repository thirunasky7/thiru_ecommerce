<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ThaiYur Admin')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-premium.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @yield('css')
</head>
<body class="ad-body">
    @include('admin.layouts.sidebar')
    <div class="ad-sidebar-backdrop" id="sidebarBackdrop"></div>

    <div id="content" class="ad-content">
        <header class="ad-topbar">
            <button class="btn-toggle" id="sidebarToggle" type="button" aria-label="Toggle menu">
                <i class="fas fa-bars"></i>
            </button>
            <div>
                <h1 class="ad-topbar__title">@yield('title', 'Dashboard')</h1>
            </div>
            <div class="ad-topbar__actions">
                <a href="{{ url('/') }}" class="btn btn-outline-dark btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i> Storefront
                </a>
                <div class="dropdown">
                    <button class="ad-avatar-btn dropdown-toggle" data-bs-toggle="dropdown" type="button">
                        {{ strtoupper(substr(optional(auth()->user())->name ?? 'A', 0, 1)) }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li class="dropdown-header">{{ optional(auth()->user())->name ?? 'Admin' }}</li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="ad-main">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        (function () {
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggle = document.getElementById('sidebarToggle');
            function closeSidebar() {
                sidebar?.classList.remove('is-open');
                backdrop?.classList.remove('show');
            }
            toggle?.addEventListener('click', function () {
                sidebar?.classList.toggle('is-open');
                backdrop?.classList.toggle('show');
            });
            backdrop?.addEventListener('click', closeSidebar);

            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const term = this.value.toLowerCase();
                    document.querySelectorAll('#sidebar .nav-item').forEach(function (item) {
                        const text = item.textContent.toLowerCase();
                        item.style.display = text.includes(term) ? '' : 'none';
                    });
                });
            }
        })();
    </script>
    @yield('js')
</body>
</html>
