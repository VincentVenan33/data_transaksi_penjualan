<!DOCTYPE html>
    <html>
        <head>
            <title>@yield('title', 'Inventory System')</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
            <style>
                .nav-link.active {
                    background-color: #0d6efd !important;
                    color: white !important;
                    font-weight: bold;
                }

                #sidebar {
                    transition: margin-left 0.3s;
                }

                #sidebar.hide {
                    margin-left: -250px;
                }

                #mainContent {
                    transition: margin-left 0.3s;

                }

                #sidebar.hide + #mainContent {
                    margin-left: 0;
                }
            </style>
        </head>
        <body>
            <div class="d-flex">
                @include('layouts.sidebar')
                <div id="mainContent" class="container-fluid p-4">
                    <button id="toggleSidebar" class="btn btn-primary mb-3">
                <i class="bi bi-list"></i>
                </button>
                    @yield('content')
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const toggleBtn = document.getElementById('toggleSidebar');
                    const sidebar = document.getElementById('sidebar');

                    if(toggleBtn && sidebar) {
                        toggleBtn.addEventListener('click', () => {
                            sidebar.classList.toggle('hide');
                        });
                    }
                });
            </script>
        </body>
    </html>
