<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="mb-4">
        <h2 class="mb-0">Détails de l'Entreprise</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('entreprises.index')); ?>">Entreprises</a></li>
                <li class="breadcrumb-item active"><?php echo e($entreprise->nom); ?></li>
            </ol>
        </nav>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Informations générales -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Informations Générales</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Code :</th>
                            <td><strong><?php echo e($entreprise->code); ?></strong></td>
                        </tr>
                        <tr>
                            <th>Nom :</th>
                            <td><?php echo e($entreprise->nom); ?></td>
                        </tr>
                        <tr>
                            <th>Matricule :</th>
                            <td><?php echo e($entreprise->matricule ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Société :</th>
                            <td><?php echo e($entreprise->societe ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Email :</th>
                            <td><?php echo e($entreprise->email ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Téléphone :</th>
                            <td><?php echo e($entreprise->telephone ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Adresse :</th>
                            <td><?php echo e($entreprise->adresse ?? '-'); ?></td>
                        </tr>
                        <tr>
                            <th>Statut :</th>
                            <td>
                                <?php if($entreprise->active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Créée le :</th>
                            <td><?php echo e($entreprise->created_at->format('d/m/Y H:i')); ?></td>
                        </tr>
                    </table>

                    <?php if($entreprise->description): ?>
                        <div class="mt-3">
                            <h6>Description :</h6>
                            <p class="text-muted"><?php echo e($entreprise->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="mt-4">
                        <a href="<?php echo e(route('entreprises.edit', $entreprise->id)); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        <a href="<?php echo e(route('entreprises.add-users', $entreprise->id)); ?>" class="btn btn-primary">
                            <i class="fas fa-users"></i> Gérer les utilisateurs
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistiques</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-4">
                            <div class="stat-box">
                                <i class="fas fa-users fa-3x text-primary mb-2"></i>
                                <h3><?php echo e($entreprise->users->count()); ?></h3>
                                <p class="text-muted">Utilisateurs</p>
                            </div>
                        </div>
                        <div class="col-6 mb-4">
                            <div class="stat-box">
                                <i class="fas fa-clipboard-list fa-3x text-success mb-2"></i>
                                <h3><?php echo e($entreprise->demandes->count()); ?></h3>
                                <p class="text-muted">Demandes</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <h6>Demandes par statut :</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-circle text-warning"></i> En attente :
                                <?php echo e($entreprise->demandes->where('statut', 'en_attente')->count()); ?>

                            </li>
                            <li><i class="fas fa-circle text-info"></i> En cours :
                                <?php echo e($entreprise->demandes->where('statut', 'en_cours')->count()); ?>

                            </li>
                            <li><i class="fas fa-circle text-success"></i> Traitées :
                                <?php echo e($entreprise->demandes->where('statut', 'traite')->count()); ?>

                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des utilisateurs -->
    <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-users"></i> Utilisateurs de l'entreprise</h5>
            <a href="<?php echo e(route('entreprises.add-users', $entreprise->id)); ?>" class="btn btn-sm btn-light">
                <i class="fas fa-plus"></i> Ajouter des utilisateurs
            </a>
        </div>
        <div class="card-body">
            <?php if($entreprise->users->count() > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Nom</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Matricule</th>
                                <th>Fonction</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $entreprise->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($user->nom); ?></td>
                                    <td><?php echo e($user->username); ?></td>
                                    <td><?php echo e($user->email ?? '-'); ?></td>
                                    <td><?php echo e($user->matricule); ?></td>
                                    <td><?php echo e($user->fonction ?? '-'); ?></td>
                                    <td>
                                        <?php if($user->type == 0): ?>
                                            <span class="badge bg-danger">Admin</span>
                                        <?php elseif($user->type == 2): ?>
                                            <span class="badge bg-info">Support</span>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Employé</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="<?php echo e(route('entreprises.detach-user', [$entreprise->id, $user->id])); ?>"
                                              method="POST"
                                              onsubmit="return confirm('Retirer cet utilisateur de l\'entreprise ?');"
                                              style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-user-minus"></i> Retirer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted py-4">Aucun utilisateur associé à cette entreprise.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        background: #f5f7fa;
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-top: 10px;
    }

    .stat-box {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
    }

    .stat-box h3 {
        color: #333;
        margin: 10px 0;
    }

    .table th {
        font-weight: 600;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/entreprises/show.blade.php ENDPATH**/ ?>