<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Login — ThaiYur')</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #10231f;
            --paper: #f4f0e8;
            --accent: #0f766e;
        }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: Outfit, sans-serif;
            background:
                radial-gradient(circle at 15% 20%, rgba(15,118,110,.18), transparent 40%),
                radial-gradient(circle at 85% 10%, rgba(196,92,38,.14), transparent 35%),
                linear-gradient(160deg, #f7f3eb, #ebe4d6);
            color: var(--ink);
        }
        .ty-admin-login {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
        }
        .ty-admin-login__card {
            width: 100%;
            max-width: 440px;
            background: rgba(255,253,248,.95);
            border: 1px solid rgba(16,35,31,.1);
            border-radius: 24px;
            padding: 2rem;
            box-shadow: 0 24px 60px rgba(16,35,31,.1);
        }
        .ty-admin-login__brand {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .ty-admin-login__mark {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: linear-gradient(145deg, #128277, #0b3d38);
            color: #fff;
            font-weight: 700;
        }
        .ty-admin-login__brand h1 {
            font-family: "Cormorant Garamond", Georgia, serif;
            font-size: 1.9rem;
            margin: 0;
            line-height: 1;
        }
        .ty-admin-login__brand p {
            margin: 0.25rem 0 0;
            color: #5c6f69;
            font-size: 0.9rem;
        }
        .form-control {
            border-radius: 12px;
            border-color: rgba(16,35,31,.15);
            background: #fff;
        }
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 .2rem rgba(15,118,110,.15);
        }
        .btn-dark {
            background: var(--ink);
            border: 0;
            border-radius: 999px;
            font-weight: 600;
            padding: .85rem 1rem;
        }
        .ty-admin-login__hint {
            margin-top: 1.25rem;
            padding: .85rem 1rem;
            border-radius: 12px;
            background: rgba(15,118,110,.08);
            color: #5c6f69;
            font-size: .85rem;
        }
        .ty-admin-login__hint code {
            color: var(--ink);
            font-weight: 600;
        }
    </style>
    @yield('css')
</head>
<body>
    @yield('content')
</body>
</html>
