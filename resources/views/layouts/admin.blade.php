<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Admin</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="brand">
                    <i class="fa-solid fa-layer-group text-primary"></i>
                    <span>Attendance.io</span>
                </div>
            </div>
            
            <nav class="sidebar-menu">
                <div class="menu-label">Main</div>
                <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                
                <div class="menu-label">Management</div>
                <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-users"></i>
                    <span>Employees</span>
                </a>
                <a href="{{ route('admin.attendances.index') }}" class="menu-item {{ request()->routeIs('admin.attendances.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Attendance</span>
                </a>
                <a href="{{ route('admin.attendances.report') }}" class="menu-item {{ request()->routeIs('admin.attendances.report') ? 'active' : '' }}">
                    <i class="fa-solid fa-file-invoice"></i>
                    <span>Reports</span>
                </a>

                <div class="menu-label">System</div>
                <a href="{{ route('admin.settings.index') }}" class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i>
                    <span>Settings</span>
                </a>
            </nav>
            
            <div class="sidebar-footer" style="position: absolute; bottom: 0; width: 100%; padding: 1.5rem; border-top: 1px solid #e5e7eb;">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-block" style="background: #fee2e2; color: #991b1b; justify-content: flex-start;">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span>Sign Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-title">
                    <h2>@yield('title', 'Dashboard')</h2>
                </div>
                
                <div class="header-actions">
                    <div class="user-profile">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random" alt="Avatar" class="avatar">
                        <div class="user-info">
                            <h4 style="font-size: 0.875rem; font-weight: 600;">{{ Auth::user()->name }}</h4>
                            <span style="font-size: 0.75rem; color: #6b7280;">Administrator</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-body">
                @if(session('success'))
                    <div class="status-badge status-success" style="width: 100%; border-radius: 6px; padding: 1rem; margin-bottom: 2rem; display: block;">
                        <i class="fa-solid fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="status-badge status-danger" style="width: 100%; border-radius: 6px; padding: 1rem; margin-bottom: 2rem; display: block;">
                        <ul style="list-style: none;">
                            @foreach ($errors->all() as $error)
                                <li><i class="fa-solid fa-exclamation-circle"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            
                @yield('content')
            </div>
        </main>
    </div>
</body>
</html>
