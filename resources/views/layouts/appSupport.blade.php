<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Support Entreprise - GIDA')</title>
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: #2c3e50;
            color: white;
            padding-top: 1rem;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            display: block;
        }
        
        .sidebar a:hover {
            background: #34495e;
        }
        
        .main-content {
            padding: 1rem;
        }
        
        .navbar {
            background: #3498db;
            padding: 1rem;
        }
        
        .navbar-brand {
            color: white;
            font-weight: bold;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            border-radius: 50%;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="mb-4">Menu Support</h4>
                <nav>
                    <a href="{{ route('supportEntreprise.dashboard') }}" class="{{ request()->routeIs('supportEntreprise.dashboard') ? 'active' : '' }}">
                        Tableau de bord
                    </a>
                    <a href="{{ route('supportEntreprise.profil') }}" class="{{ request()->routeIs('supportEntreprise.profil') ? 'active' : '' }}">
                        Mon Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm w-100">Déconnexion</button>
                    </form>
                </nav>
            </div>

            <!-- Main content -->
            <div class="col-md-10 main-content">
                <!-- Navbar -->
                <nav class="navbar mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand">{{ auth()->user()->entreprise->nom ?? 'Support Entreprise' }}</span>
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <span class="notification-badge">{{ $nombre_notification_employe ?? 0 }}</span>
                                <i class="fas fa-bell"></i>
                            </div>
                            <span>{{ auth()->user()->nom }}</span>
                        </div>
                    </div>
                </nav>

                <!-- Main content area -->
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>