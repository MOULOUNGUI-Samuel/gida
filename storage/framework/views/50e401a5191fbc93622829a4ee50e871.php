<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Support Entreprise - GIDA'); ?></title>
    
    <!-- Styles -->
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
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
                    <a href="<?php echo e(route('supportEntreprise.dashboard')); ?>" class="<?php echo e(request()->routeIs('supportEntreprise.dashboard') ? 'active' : ''); ?>">
                        Tableau de bord
                    </a>
                    <a href="<?php echo e(route('supportEntreprise.profil')); ?>" class="<?php echo e(request()->routeIs('supportEntreprise.profil') ? 'active' : ''); ?>">
                        Mon Profil
                    </a>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-danger btn-sm w-100">Déconnexion</button>
                    </form>
                </nav>
            </div>

            <!-- Main content -->
            <div class="col-md-10 main-content">
                <!-- Navbar -->
                <nav class="navbar mb-4">
                    <div class="container-fluid">
                        <span class="navbar-brand"><?php echo e(auth()->user()->entreprise->nom ?? 'Support Entreprise'); ?></span>
                        <div class="d-flex align-items-center">
                            <div class="position-relative me-3">
                                <span class="notification-badge"><?php echo e($nombre_notification_employe ?? 0); ?></span>
                                <i class="fas fa-bell"></i>
                            </div>
                            <span><?php echo e(auth()->user()->nom); ?></span>
                        </div>
                    </div>
                </nav>

                <!-- Main content area -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/layouts/appSupport.blade.php ENDPATH**/ ?>