<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <title>{{ config('app.name', 'GIDA') }}</title>
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
        .gida-sidebar { 
            background: #0e2248; 
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
        
        .gida-sidebar header { 
            padding: 32px 24px 24px; 
            font-weight: bold; 
            font-size: 1.8em; 
            letter-spacing: 2px; 
            border-bottom: 1px solid #32416b; 
        }
        
        .gida-sidebar nav { 
            flex: 1; 
            padding: 20px 0;
        }
        
        .gida-sidebar ul { 
            list-style: none; 
            padding: 0; 
            margin: 0; 
        }
        
        .gida-sidebar li { 
            padding: 16px 24px; 
            cursor: pointer; 
            transition: background 0.2s; 
            display: flex; 
            align-items: center;
            margin: 4px 0;
        }
        
        .gida-sidebar li:hover, 
        .gida-sidebar .active { 
            background: #192c56; 
        }
        
        .gida-sidebar .icon { 
            margin-right: 16px; 
            font-size: 1.2em;
            width: 24px;
            text-align: center;
        }
        
        .gida-sidebar li span {
            font-size: 1em;
            font-weight: 500;
        }
        
        /* Main Content */
        .gida-main { 
            margin-left: 280px; 
            min-height: 100vh; 
            padding: 32px 40px; 
            width: calc(100% - 280px);
        }
        
        /* Header */
        .gida-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }
        
        .gida-header h1 {
            font-size: 2.2em;
            font-weight: bold;
            color: #333;
        }
        
        .gida-btn {
            padding: 12px 24px;
            background: #2769a2;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            display: inline-block;
        }
        
        .gida-btn:hover {
            background: #19476d;
        }
        
        /* Dashboard Card */
        .gida-dashboard {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
            padding: 32px;
            margin-bottom: 32px;
        }
        
        .gida-dashboard h2 {
            font-size: 1.5em;
            font-weight: bold;
            color: #333;
            margin-bottom: 24px;
        }
        
        /* Table */
        .gida-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .gida-table th,
        .gida-table td {
            padding: 16px 12px;
            text-align: left;
            border-bottom: 1px solid #ebecf2;
        }
        
        .gida-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #333;
            font-size: 0.95em;
        }
        
        .gida-table td {
            color: #555;
        }
        
        /* Status colors */
        .status-en cours {
            color: #ff9800;
            font-weight: bold;
        }
        
        .status-clos,
        .status-clôturé {
            color: #4caf50;
            font-weight: bold;
        }
        
        .status-en attente {
            color: #2196f3;
            font-weight: bold;
        }
        
        .status-à risque {
            color: #f44336;
            font-weight: bold;
        }
        
        /* No demands message */
        .no-demandes {
            text-align: center;
            padding: 3rem 2rem;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 1rem 0;
            border: 2px dashed #dee2e6;
        }
        
        .no-demandes p {
            margin-bottom: 1.5rem;
            color: #666;
            font-size: 1.1em;
        }
        
        /* Active navigation item */
        .gida-sidebar li.active {
            background: #192c56;
            border-left: 4px solid #2769a2;
        }
        
        /* Logout button */
        .gida-sidebar .logout {
            margin-top: auto;
            border-top: 1px solid #32416b;
            background:
        }
        
        .gida-sidebar .logout:hover {
            background:
        }
        
        /* Responsive */
        @media (max-width: 1024px) {
            .gida-sidebar {
                width: 240px;
            }
            .gida-main {
                margin-left: 240px;
                width: calc(100% - 240px);
                padding: 24px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .gida-sidebar {
                width: 60px;
            }
            .gida-main {
                margin-left: 60px;
                width: calc(100% - 60px);
                padding: 16px;
            }
            .gida-sidebar header {
                font-size: 1.2em;
                padding: 20px 0;
                text-align: center;
            }
            .gida-sidebar li {
                padding: 16px 8px;
                justify-content: center;
            }
            .gida-sidebar .icon {
                margin: 0;
            }
            .gida-sidebar li span {
                display: none;
            }
            .gida-header h1 {
                font-size: 1.8em;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="gida-sidebar">
        <header>GIDA</header>
        <nav>
            <ul>
                <li onclick="window.location.href='{{ route('dashboardEmployer') }}'">
                    <span class="icon">🏠</span>
                    <span>Dashboard</span>
                </li>
                <li onclick="window.location.href='{{ route('nouvelledemande') }}'">
                    <span class="icon">➕</span>
                    <span>Nouvelle Demande</span>
                </li>
                <li onclick="window.location.href='{{ route('historique') }}'">
                    <span class="icon">📄</span>
                    <span>Historique</span>
                </li>

                <li onclick="window.location.href='{{ route('profil') }}'">
                    <span class="icon">👤</span>
                    <span>Profil</span>
                </li>
            
            
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
    <main class="gida-main" aria-label="Contenu principal">
        @yield('content')
    </main>
    
    <script>
        // Gestion de l'état actif de la navigation
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.gida-sidebar li');
            
            navItems.forEach(item => {
                const link = item.getAttribute('onclick');
                if (link) {
                    const route = link.match(/route\('([^']+)'\)/);
                    if (route) {
                        const routeName = route[1];
                        if (currentPath.includes(routeName) || 
                            (routeName === 'dashboardEmployer' && currentPath === '/dashboardEmployer') ||
                            (routeName === 'nouvelledemande' && currentPath === '/Nouvelle-demande') ||
                            (routeName === 'historique' && currentPath === '/historique') ||
                            (routeName === 'messagerie' && currentPath === '/messagerie') ||
                            (routeName === 'evaluation' && currentPath === '/evaluation') ||
                            (routeName === 'notifications' && currentPath === '/notifications') ||
                            (routeName === 'profil' && currentPath === '/profil')) {
                            item.classList.add('active');
                        }
                    }
                }
            });
        });
    </script>
    
    <script src="https://cdn.botpress.cloud/webchat/v3.3/inject.js"></script>
    <script src="https://files.bpcontent.cloud/2025/09/19/10/20250919104115-4HFJVBBO.js" defer></script>
    
</body>
</html>
