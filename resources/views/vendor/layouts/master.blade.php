<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Panel — ThaiYur')</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        body { font-family: Outfit, sans-serif; background: #f4f0e8; margin: 0; }
        #sidebar {
            width: 260px; min-height: 100vh; background: #0b1c18; color: #fff;
            position: fixed; left: 0; top: 0; padding: 1.25rem !important; z-index: 100;
        }
        #sidebar .nav-link { color: rgba(255,255,255,.8); border-radius: 10px; margin-bottom: 4px; }
        #sidebar .nav-link:hover, #sidebar .nav-link.active { background: rgba(255,255,255,.1); color: #fff; }
        #content { margin-left: 260px; min-height: 100vh; }
        @media (max-width: 991px) {
            #sidebar { transform: translateX(-100%); transition: .25s; }
            #sidebar.show { transform: none; }
            #content { margin-left: 0; }
        }
    </style>
    @yield('css')
</head>
<body>
    @include('vendor.layouts.sidebar')

    <div id="content" class="w-100">
        <nav class="navbar navbar-expand navbar-light bg-white border-bottom px-3 py-2">
            <button class="btn btn-dark d-lg-none" id="sidebarToggle" type="button"><i class="fas fa-bars"></i></button>
            <div class="ms-auto d-flex align-items-center gap-3">
                <span class="text-muted small">{{ optional(Auth::guard('vendor')->user())->business_name ?? optional(Auth::guard('vendor')->user())->name }}</span>
                <form action="{{ route('vendor.logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-dark btn-sm" type="submit">Logout</button>
                </form>
            </div>
        </nav>
        <div class="container-fluid py-4">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('sidebar')?.classList.toggle('show');
        });
    </script>
    @yield('js')
</body>
</html>
