<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skillio Admin')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f4f8fb;
            font-family: 'Segoe UI', sans-serif;
        }

        /* SIDEBAR */
        .sidebar {
            width: 14rem;
            position: fixed;
            height: 100vh;
            background: linear-gradient(180deg, #1F2A6C, #1CA7A6, #8BC34A);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }

        .sidebar h4 {
            text-align: center;
            padding: 20px 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.2);
            padding-left: 25px;
        }

        .sidebar a.active {
            background: rgba(255, 255, 255, 0.3);
            border-left: 4px solid #fff;
        }

        /* CONTENT WRAPPER */
        #content-wrapper {
            margin-left: 14rem;
            min-height: 100vh;
        }

        /* TOPBAR */
        .topbar {
            height: 60px;
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* BUTTON */
        .btn-primary,
        .btn-success {
            background: linear-gradient(45deg, #1CA7A6, #8BC34A);
            border: none;
            color: white;
        }

        .btn-primary:hover,
        .btn-success:hover {
            opacity: 0.9;
        }

        /* CARD */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* TABLE */
        .table thead {
            background: linear-gradient(90deg, #1F2A6C, #1CA7A6);
            color: white;
        }

        .table thead th {
            border: none;
            padding: 12px;
        }

        .badge {
            padding: 6px 12px;
            font-weight: 500;
            border-radius: 6px;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- SIDEBAR --}}
    <div class="sidebar">
        <h4>Skillio</h4>

        <a href="{{ url('/admin/dashboard') }}" class="{{ request()->is('admin/dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>

        <a href="{{ url('/admin/courses') }}" class="{{ request()->is('admin/courses*') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Paket Kursus
        </a>

        <a href="{{ url('/admin/categories') }}" class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
            <i class="fas fa-folder"></i> Kategori
        </a>

        <a href="{{ url('/admin/instructors') }}" class="{{ request()->is('admin/instructors*') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Pengajar
        </a>

        <a href="{{ url('/admin/users') }}" class="{{ request()->is('admin/users*') ? 'active' : '' }}">
            <i class="fas fa-user"></i> Data User
        </a>


        <div style="padding: 20px;">
            <button class="btn btn-light w-100" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    {{-- CONTENT WRAPPER --}}
    <div id="content-wrapper">

        {{-- TOPBAR --}}
        <div class="topbar">
            <div><b>Admin Panel</b></div>
            <div><b>Admin Skillio</b></div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="container-fluid p-4">
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

</body>
</html>