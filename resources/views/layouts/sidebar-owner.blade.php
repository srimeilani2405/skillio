<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Skillio Owner')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: #f4f8fb; font-family: 'Segoe UI', sans-serif; }

        .sidebar {
            width: 14rem;
            position: fixed;
            height: 100vh;
            background: linear-gradient(180deg, #1F2A6C, #1CA7A6, #8BC34A);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        .sidebar h4 { text-align: center; padding: 20px 0; font-weight: bold; letter-spacing: 1px; }
        .sidebar a {
            color: white;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }
        .sidebar a:hover { background: rgba(255,255,255,0.2); padding-left: 25px; }
        .sidebar a.active { background: rgba(255,255,255,0.3); border-left: 4px solid #fff; }

        #content-wrapper { margin-left: 14rem; min-height: 100vh; }

        .topbar {
            height: 60px;
            background: white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .btn-primary, .btn-success {
            background: linear-gradient(45deg, #1CA7A6, #8BC34A);
            border: none;
            color: white;
        }
        .btn-primary:hover, .btn-success:hover { opacity: 0.9; }

        .card { border: none; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

        .table thead { background: linear-gradient(90deg, #1F2A6C, #1CA7A6); color: white; }
        .table thead th { border: none; padding: 12px; }

        .badge { padding: 6px 12px; font-weight: 500; border-radius: 6px; }
        
        .dropdown-toggle::after {
            display: inline-block;
            margin-left: 0.5rem;
        }
    </style>

    @stack('styles')
</head>
<body>

    <div class="sidebar">
        <h4>Skillio</h4>

        <a href="{{ route('owner.dashboard') }}" class="{{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>

        <a href="{{ route('owner.products.index') }}" class="{{ request()->routeIs('owner.products*') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Paket Kursus
        </a>

        <a href="{{ route('owner.users.index') }}" class="{{ request()->routeIs('owner.users*') ? 'active' : '' }}">
            <i class="fas fa-users-cog"></i> Kelola User
        </a>

        <a href="{{ route('owner.transactions.index') }}" class="{{ request()->routeIs('owner.transactions*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Laporan Transaksi
        </a>

        <a href="{{ route('owner.activity-logs.index') }}" class="{{ request()->routeIs('owner.activity-logs*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> Activity Log
        </a>
    </div>

    <div id="content-wrapper">
        <div class="topbar">
            <div><b>Owner Panel</b></div>
            
            {{-- DROPDOWN PROFIL DI KANAN ATAS --}}
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                        style="border-radius: 30px; padding: 5px 15px;">
                    <i class="fas fa-user-circle fa-lg"></i>
                    <span>{{ Auth::user()->name ?? 'Owner' }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="min-width: 220px;">
                    <li>
                        <a class="dropdown-item" href="{{ route('owner.profile') }}">
                            <i class="fas fa-user-edit text-primary me-2"></i> Profil Saya
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form-topbar">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>