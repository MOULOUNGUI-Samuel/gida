<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestion des Entreprises</h2>
        <a href="<?php echo e(route('entreprises.create')); ?>" class="admin-btn">
            <i class="fas fa-plus"></i> Nouvelle Entreprise
        </a>
    </div>

    <?php echo $__env->make('partials.flash-messages', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <div class="table-responsive">
                    <table class="admin-table" id="users-table">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Nom</th>
                            <th>Matricule</th>
                            <th>Société</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Utilisateurs</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $entreprises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entreprise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($entreprise->code); ?></strong></td>
                                <td><?php echo e($entreprise->nom); ?></td>
                                <td><?php echo e($entreprise->matricule ?? '-'); ?></td>
                                <td><?php echo e($entreprise->societe ?? '-'); ?></td>
                                <td><?php echo e($entreprise->email ?? '-'); ?></td>
                                <td><?php echo e($entreprise->telephone ?? '-'); ?></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo e($entreprise->users_count); ?> utilisateur(s)
                                    </span>
                                </td>
                                <td>
                                    <?php if($entreprise->active): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('entreprises.show', $entreprise->id)); ?>"
                                           class="btn btn-sm btn-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('entreprises.edit', $entreprise->id)); ?>"
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('entreprises.add-users', $entreprise->id)); ?>"
                                           class="btn btn-sm btn-primary" title="Gérer les utilisateurs">
                                            <i class="fas fa-users"></i>
                                        </a>
                                        <form action="<?php echo e(route('entreprises.toggle-active', $entreprise->id)); ?>"
                                              method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-secondary"
                                                    title="<?php echo e($entreprise->active ? 'Désactiver' : 'Activer'); ?>">
                                                <i class="fas fa-power-off"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('entreprises.destroy', $entreprise->id)); ?>"
                                              method="POST"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?');"
                                              style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <p class="text-muted mb-0">Aucune entreprise enregistrée.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
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

    .table th {
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #dee2e6;
    }

    .btn-group .btn {
        margin: 0 2px;
    }

    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/entreprises/index.blade.php ENDPATH**/ ?>