<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GIDA') }} - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Segoe UI', Arial, sans-serif; 
            background: #f5f7fa; 
            display: flex;
        }

        .nombre-notification {
            position: absolute;
            right: 10px;      /* Décalage à droite */
            background: red;
            color: white;
            border-radius: 50%;
            padding: 4px 8px;
            padding-top : 1px;
            font-size: 12px;
            font-weight: bold;
            line-height: 1;
            min-width: 20px;
            text-align: center;
        }

        /* Sidebar */
        .admin-sidebar { 
            background: #1a1a2e; 
            color: #fff; 
            width: 280px; 
            min-height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            display: flex; 
            flex-direction: column; 
            z-index: 10; 
        }
        
        .admin-sidebar header { 
            padding: 32px 24px 24px; 
            font-weight: bold; 
            font-size: 1.8em; 
            letter-spacing: 2px; 
            border-bottom: 1px solid #16213e; 
            background: #16213e;
        }
        
        .admin-sidebar nav { 
            flex: 1; 
            padding: 20px 0;
        }
        
        .admin-sidebar ul { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
        }
        
        .admin-sidebar li { 
            padding: 16px 24px; 
            cursor: pointer; 
            transition: background 0.2s; 
            display: flex; 
            align-items: center;
            margin: 4px 0;
            border-left: 4px solid transparent;
        }
        
        .admin-sidebar li:hover, 
        .admin-sidebar li.active { 
            background: #16213e; 
            border-left-color: #0f3460;
        }
        
        .admin-sidebar .icon { 
            margin-right: 16px; 
            font-size: 1.2em;
            width: 24px;
            text-align: center;
        }
        
        .admin-sidebar li span {
            font-size: 1em;
            font-weight: 500;
        }
        
        .admin-sidebar .logout {
            margin-top: auto;
            border-top: 1px solid #16213e;
            background:
        }
        
        .admin-sidebar .logout:hover {
            background:
        }
        
        /* Main Content */
        .admin-main { 
            margin-left: 280px; 
            min-height: 100vh; 
            padding: 32px 40px; 
            width: calc(100% - 280px);
        }
        
        /* Header */
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e0e6ed;
        }
        
        .admin-header h1 {
            font-size: 2.2em;
            font-weight: bold;
            color: #1a1a2e;
        }
        
        .admin-btn {
            padding: 12px 24px;
            background: #0f3460;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .admin-btn:hover {
            background: #16213e;
        }
        
        .admin-btn.secondary {
            background: #6c757d;
        }
        
        .admin-btn.secondary:hover {
            background: #5a6268;
        }
        
        /* Dashboard Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-bottom: 32px;
        }
        
        .stat-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
            padding: 24px;
            text-align: center;
        }
        
        .stat-card h3 {
            color: #6c757d;
            font-size: 0.9em;
            font-weight: 600;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-card .number {
            font-size: 2.5em;
            font-weight: bold;
            color: #0f3460;
            margin-bottom: 8px;
        }
        
        .stat-card .trend {
            font-size: 0.9em;
            color: #28a745;
        }
        
        /* Table */
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
        }
        
        .admin-table th,
        .admin-table td {
            padding: 16px 12px;
            text-align: left;
            border-bottom: 1px solid #e0e6ed;
        }
        
        .admin-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 0.95em;
        }
        
        .admin-table td {
            color: #555;
        }
        
        .admin-table tr:hover {
            background: #f8f9fa;
        }
        
        /* Status badges */
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-progress {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .status-risk {
            background: #f8d7da;
            color: #721c24;
        }
        
        .status-closed {
            background: #d4edda;
            color: #155724;
        }
        
        /* Filters */
        .filters {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            align-items: center;
        }
        
        .filters input,
        .filters select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9em;
        }
        
        .filters input {
            min-width: 200px;
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .admin-sidebar {
                width: 240px;
            }
            .admin-main {
                margin-left: 240px;
                width: calc(100% - 240px);
                padding: 24px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 60px;
            }
            .admin-main {
                margin-left: 60px;
                width: calc(100% - 60px);
                padding: 16px;
            }
            .admin-sidebar header {
                font-size: 1.2em;
                padding: 20px 0;
                text-align: center;
            }
            .admin-sidebar li {
                padding: 16px 8px;
                justify-content: center;
            }
            .admin-sidebar .icon {
                margin: 0;
            }
            .admin-sidebar li span {
                display: none;
            }
            .admin-header h1 {
                font-size: 1.8em;
            }
            .filters {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <header>{{ auth()->user()->type == 0 ? 'GIDA ADMIN' : 'SUPPORT' }}</header>
        <nav>
            <ul>
                @if(auth()->user()->type == 0)
                <!-- Menu Admin -->
                <li onclick="window.location.href='{{ route('dashboard') }}'" class="nav-dashboard">
                    <span class="icon">📊</span>
                    <span>Dashboard</span>
                </li>
                <li onclick="window.location.href='{{ route('reporting') }}'" class="nav-reporting">
                    <span class="icon">📈</span>
                    <span>Reporting & KPI</span>
                </li>
                <li onclick="window.location.href='{{ route('users') }}'" class="nav-users">
                    <span class="icon">👥</span>
                    <span>Gestion des utilisateurs</span>
                </li>
                <li onclick="window.location.href='{{ route('entreprises.index') }}'" class="nav-entreprises">
                    <span class="icon">🏢</span>
                    <span>Gestion des entreprises</span>
                </li>
                <li onclick="window.location.href='{{ route('notificationAdmin') }}'">
                    <span class="icon">🔔</span>
                    <span>Notifications</span>
                    @if($nombre_notification_admin > 0)
                        <span class="nombre-notification">{{ $nombre_notification_admin }}</span>
                    @endif
                </li>
                @else
                <!-- Menu Support Entreprise -->
                <li onclick="window.location.href='{{ route('supportEntreprise.home') }}'" class="nav-home">
                    <span class="icon">🏠</span>
                    <span>Accueil</span>
                </li>
                <li onclick="window.location.href='{{ route('supportEntreprise.dashboard') }}'" class="nav-dashboard">
                    <span class="icon">📥</span>
                    <span>Demandes</span>
                </li>
                <li onclick="window.location.href='{{ route('supportEntreprise.historique') }}'" class="nav-historique">
                    <span class="icon">🕓</span>
                    <span>Historique</span>
                </li>
                <li onclick="window.location.href='{{ route('supportEntreprise.profil') }}'" class="nav-profil">
                    <span class="icon">👤</span>
                    <span>Profil</span>
                </li>
                <li onclick="window.location.href='{{ route('supportEntreprise.notifications') }}'" class="nav-notifications">
                    <span class="icon">🔔</span>
                    <span>Notifications</span>
                </li>
                @endif

                <li class="logout">
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0; padding: 0;">
                        @csrf
                        <button type="submit" style="background: none; border: none; color: inherit; cursor: pointer; display: flex; align-items: center; width: 100%; padding: 0; font: inherit;">
                            <span class="icon">🚪</span>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </li>
      </ul>
    </nav>
    </aside>

    <!-- Contenu principal -->
    <main class="admin-main" aria-label="Contenu principal">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> 
    <script>
        // Gestion des notifications
        function checkNotifications() {
            // Cette fonction sera implémentée plus tard si nécessaire
        }

        // Active le menu courant
        function setActiveMenuItem() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.admin-sidebar li');
            
            navItems.forEach(item => {
                const link = item.getAttribute('onclick');
                if (!link) return;

                const route = link.match(/route\('([^']+)'\)/);
                if (!route) return;

                const routeName = route[1];
                const isActive = currentPath.includes(routeName) || 
                    (routeName === 'dashboard' && currentPath === '/dashboard') ||
                    (routeName === 'reporting' && currentPath === '/reporting') ||
                    (routeName === 'users' && currentPath === '/users') ||
                    (routeName === 'supportEntreprise.home' && currentPath === '/support-entreprise/home') ||
                    (routeName === 'supportEntreprise.dashboard' && (currentPath === '/support-entreprise' || currentPath === '/support-entreprise/dashboard')) ||
                    (routeName === 'supportEntreprise.historique' && currentPath === '/support-entreprise/historique') ||
                    (routeName === 'supportEntreprise.profil' && currentPath === '/support-entreprise/profil') ||
                    (routeName === 'supportEntreprise.notifications' && currentPath === '/support-entreprise/notifications');

                if (isActive) {
                    item.classList.add('active');
                }
            });
        }

        // Initialisation
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setActiveMenuItem);
        } else {
            setActiveMenuItem();
        }

        // Auto-fermeture des alertes après 5 secondes
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
</body>
</html>
