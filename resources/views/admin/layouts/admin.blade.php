<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome & Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('/public/css/app.css') }}">

    @yield('css')
</head>
<body>
    @include('admin.layouts.sidebar')
    
    <!-- Content Area -->
    <div id="content" class="w-100">
        <nav class="navbar navbar-expand navbar-light bg-light p-3">
            <button class="btn btn-dark" id="sidebarToggle"><i class="fas fa-bars"></i></button>

            <!-- Language Dropdown -->
            <div class="dropdown ms-auto me-3">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://flagcdn.com/w40/us.png" width="20">{{ __('cms.languages.english') }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item language-select {{ app()->getLocale() == 'en' ? 'active' : '' }}" data-lang="en" href="#"><img src="https://flagcdn.com/w40/us.png" width="20">{{ __('cms.languages.english') }}</a></li>
                    <li><a class="dropdown-item language-select" data-lang="es" href="#"><img src="https://flagcdn.com/w40/es.png" width="20">{{ __('cms.languages.spanish') }}</a></li>
                    <!-- Add other languages as needed -->
                </ul>
            </div>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="https://via.placeholder.com/40" class="rounded-circle" alt="Profile">
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li>
                        <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <!-- Modal for Confirmation -->
    <div class="modal fade" id="languageChangeModal" tabindex="-1" aria-labelledby="languageChangeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="languageChangeModalLabel">{{ __('cms.languages.change_language') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{ __('cms.languages.confirm_language_change') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('cms.languages.cancel') }}</button>
                    <button type="button" id="confirmChange" class="btn btn-primary">{{ __('cms.languages.yes_change') }}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('/public/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById("searchInput");
            const menuItems = document.querySelectorAll(".nav-item");

            if(searchInput) {
                searchInput.addEventListener("input", function () {
                    const searchTerm = searchInput.value.toLowerCase();
                    menuItems.forEach((item) => {
                        let linkTexts = item.querySelectorAll(".nav-link");
                        let matchFound = false;

                        linkTexts.forEach((link) => {
                            if (link.textContent.toLowerCase().includes(searchTerm)) {
                                matchFound = true;
                                link.closest(".nav-item").style.display = "block";
                            } else {
                                link.closest(".nav-item").style.display = "none";
                            }
                        });

                        let submenu = item.querySelector(".collapse");
                        if (submenu) {
                            let childLinks = submenu.querySelectorAll(".nav-link");
                            childLinks.forEach((childLink) => {
                                if (childLink.textContent.toLowerCase().includes(searchTerm)) {
                                    matchFound = true;
                                }
                            });

                            if (matchFound) {
                                item.style.display = "block";
                                submenu.classList.add("show");
                            } else {
                                item.style.display = "none";
                                submenu.classList.remove("show");
                            }
                        }
                    });
                });
            }
        });
    </script>

    @yield('js')
</body>
</html>
