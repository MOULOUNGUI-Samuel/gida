

<?php $__env->startSection('title', 'Profil Support - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <div class="support-profile">
        <h1>Profil Support</h1>

        <div class="profile-card">
            <div class="profile-header">
                <div class="avatar">
                    <?php if($user->photo): ?>
                        <img src="<?php echo e(asset('storage/' . $user->photo)); ?>" alt="Photo de profil">
                    <?php else: ?>
                        <div class="initials"><?php echo e(strtoupper(substr($user->nom, 0, 2))); ?></div>
                    <?php endif; ?>
                </div>
                <div class="user-info">
                    <h2><?php echo e($user->nom); ?></h2>
                    <p class="member-since">Membre depuis <?php echo e($user->created_at->format('F Y')); ?></p>
                </div>
            </div>

            <div class="info-section">
                <h3>Informations personnelles</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <label>NOM COMPLET</label>
                        <p><?php echo e($user->nom); ?></p>
                    </div>
                    <div class="info-item">
                        <label>EMAIL</label>
                        <p><?php echo e($user->email); ?></p>
                    </div>
                    <div class="info-item">
                        <label>SOCIÉTÉ</label>
                        <p><?php echo e($user->entreprise->nom ?? $user->societe); ?></p>
                    </div>
                    <div class="info-item">
                        <label>FONCTION</label>
                        <p><?php echo e($user->fonction); ?></p>
                    </div>
                </div>
            </div>

            <div class="stats-section">
                <h3>Activité</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="number"><?php echo e($stats['total'] ?? 0); ?></span>
                        <span class="label">Demandes totales</span>
                    </div>
                    <div class="stat-item">
                        <span class="number"><?php echo e($stats['resolues'] ?? 0); ?></span>
                        <span class="label">Demandes résolues</span>
                    </div>
                    <div class="stat-item">
                        <span class="number"><?php echo e($stats['en_cours'] ?? 0); ?></span>
                        <span class="label">En cours</span>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button id="editProfileBtn" class="btn btn-primary" onclick="location.href='<?php echo e(route('supportEntreprise.profil.edit')); ?>'">
                    Modifier le profil
                </button>
                <button class="btn btn-secondary" onclick="location.href='<?php echo e(route('supportEntreprise.dashboard')); ?>'">
                    Retour
                </button>
            </div>
        </div>
    </div>

    <style>
        .support-profile {
            padding: 2rem;
        }

        .profile-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
            max-width: 800px;
            margin: 0 auto;
        }

        .profile-header {
            display: flex;
            align-items: center;
            margin-bottom: 2rem;
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #0066cc;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
        }

        .initials {
            color: white;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .info-section, .stats-section {
            margin-bottom: 2rem;
        }

        .info-grid, .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .info-item label {
            color: #666;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
            display: block;
        }

        .info-item p {
            margin: 0;
            font-weight: 500;
            color: #333;
        }

        .stat-item {
            text-align: center;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .stat-item .number {
            display: block;
            font-size: 1.5rem;
            font-weight: bold;
            color: #0066cc;
        }

        .stat-item .label {
            font-size: 0.875rem;
            color: #666;
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 2rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #0066cc;
            color: white;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn:hover {
            opacity: 0.9;
        }

        h3 {
            color: #333;
            border-bottom: 2px solid #eee;
            padding-bottom: 0.5rem;
        }

        .member-since {
            color: #666;
            margin: 0;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/supportEntreprise/profil.blade.php ENDPATH**/ ?>