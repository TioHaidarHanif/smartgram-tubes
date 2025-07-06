<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Smartgram') }} - @yield('title', 'Social Learning Platform')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 600;
            color: #3b82f6 !important;
        }
        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }
        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        .sidebar .nav-link {
            color: #6b7280;
            padding: 10px 15px;
            margin-bottom: 5px;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #f3f4f6;
            color: #3b82f6;
        }
        .post-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .post-header {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
        }
        .post-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 12px;
        }
        .post-content {
            padding: 15px;
        }
        .post-actions {
            display: flex;
            align-items: center;
            padding: 15px;
            border-top: 1px solid #e5e7eb;
            gap: 20px;
        }
        .post-action {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #6b7280;
            text-decoration: none;
            transition: color 0.2s;
        }
        .post-action:hover {
            color: #3b82f6;
        }
        .notification-badge {
            background: #ef4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            position: absolute;
            top: -8px;
            right: -8px;
        }
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-graduation-cap me-2"></i>
                Smartgram
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('posts.index') }}">Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('forum.index') }}">Forum</a>
                        </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('help.index') }}">Help</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <!-- Search -->
                    <form class="me-3" action="{{ route('search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search..." 
                                   value="{{ request('q') }}" style="max-width: 200px;">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    @auth
                        <!-- Notifications -->
                        <div class="dropdown me-3">
                            <a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge" id="notificationCount" style="display: none;"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" style="width: 300px;">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><div id="notificationList" class="px-3 py-2 text-muted">Loading...</div></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('notifications.index') }}">View All</a></li>
                            </ul>
                        </div>
                        
                        <!-- User Menu -->
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" data-bs-toggle="dropdown">
                                <img src="{{ auth()->user()->avatar ?? 'https://via.placeholder.com/32' }}" 
                                     class="rounded-circle me-2" width="32" height="32" alt="Avatar">
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile', auth()->user()->username) }}">
                                    <i class="fas fa-user me-2"></i>Profile
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('posts.my-posts') }}">
                                    <i class="fas fa-edit me-2"></i>My Posts
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('posts.create') }}">
                                    <i class="fas fa-plus me-2"></i>Create Post
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white mt-5 py-5 border-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Smartgram</h5>
                    <p class="text-muted">A social learning platform for knowledge sharing and collaboration.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('about') }}" class="text-muted me-3">About</a>
                    <a href="{{ route('contact') }}" class="text-muted me-3">Contact</a>
                    <a href="{{ route('help.index') }}" class="text-muted">Help</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // CSRF token for AJAX requests
        document.querySelector('meta[name="csrf-token"]').setAttribute('content', '{{ csrf_token() }}');

        // Load notifications
        @auth
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            setInterval(loadNotifications, 60000); // Check every minute
        });

        function loadNotifications() {
            fetch('/notifications/recent')
                .then(response => response.json())
                .then(data => {
                    const count = data.notifications.filter(n => !n.read_at).length;
                    const badge = document.getElementById('notificationCount');
                    
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }
                    
                    const list = document.getElementById('notificationList');
                    if (data.notifications.length === 0) {
                        list.innerHTML = '<small class="text-muted">No notifications</small>';
                    } else {
                        list.innerHTML = data.notifications.slice(0, 5).map(n => `
                            <div class="notification-item mb-2 p-2 rounded ${n.read_at ? 'bg-light' : 'bg-primary bg-opacity-10'}">
                                <div class="fw-bold">${n.title}</div>
                                <div class="small text-muted">${n.message}</div>
                                <div class="small text-muted">${new Date(n.created_at).toLocaleDateString()}</div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => console.error('Error loading notifications:', error));
        }
        @endauth
    </script>
    @stack('scripts')
</body>
</html>