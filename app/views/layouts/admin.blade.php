<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>

    <!-- Fonts: Plus Jakarta Sans (Modern Font) -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
            --primary-color: #3b82f6;
            --bg-body: #f1f5f9;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body);
            overflow-x: hidden;
        }

        /* Layout Structure */
        .admin-wrapper {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .admin-sidebar {
            width: 260px;
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
            box-shadow: 4px 0 24px 0 rgba(0,0,0,0.1);
        }

        .admin-content {
            flex-grow: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: all 0.3s ease-in-out;
            width: calc(100% - 260px);
        }

        /* Sidebar Styling */
        .admin-sidebar-inner {
            background-color: var(--sidebar-bg) !important;
        }

        .nav-link {
            border-radius: 8px;
            margin-bottom: 4px;
            padding: 10px 15px;
            transition: all 0.2s;
            font-weight: 500;
            color: #94a3b8 !important;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover);
            color: #fff !important;
            transform: translateX(4px);
        }

        .nav-link.active {
            background-color: var(--primary-color) !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.5);
        }

        .nav-link i.icon-width {
            width: 24px;
            text-align: center;
        }
        
        .fs-7 { font-size: 0.75rem; }

        /* Sidebar Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                margin-left: -260px;
            }

            .admin-sidebar.active {
                margin-left: 0;
            }

            .admin-content {
                margin-left: 0;
                width: 100%;
            }
        }

        /* Card Styling Enhancement */
        .card {
            border: none;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            background: #fff;
            transition: transform 0.2s;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
        }

        /* Utility */
        .img-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>

    @yield('styles')
</head>

<body>

    <div class="admin-wrapper">
        <!-- 1. Sidebar -->
        <aside class="admin-sidebar" id="sidebarMenu">
            @include('partials.admin.sidebar')
        </aside>

        <!-- Main Content Wrapper -->
        <main class="admin-content">
            <!-- 2. Header -->
            @include('partials.admin.header')

            <!-- Content Body -->
            <div class="container-fluid p-4 flex-grow-1">
                <!-- Breadcrumb could go here -->
                @yield('content')
            </div>

            <!-- 3. Footer -->
            @include('partials.admin.footer')
        </main>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Toggle
            var sidebarToggle = document.querySelector('[data-bs-target="#sidebarMenu"]');
            var sidebar = document.getElementById('sidebarMenu');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }

            // Preview Image Logic (Global)
            window.previewImage = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        var img = document.getElementById('preview_img');
                        if(img) {
                            img.src = e.target.result;
                            img.style.display = 'block';
                        }
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Confirm Delete (Global)
            window.confirmDelete = function() {
                return confirm('Bạn có chắc chắn muốn xóa mục này không? Hành động này không thể hoàn tác.');
            }
        });
    </script>

    @yield('scripts')
</body>

</html>