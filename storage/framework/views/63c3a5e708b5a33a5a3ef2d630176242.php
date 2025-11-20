<?php $__env->startSection('content'); ?>
<div class="container-fluid p-4">
    <div class="mb-4">
        <h2 class="mb-0">Gérer les Utilisateurs - <?php echo e($entreprise->nom); ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('entreprises.index')); ?>">Entreprises</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('entreprises.show', $entreprise->id)); ?>"><?php echo e($entreprise->nom); ?></a></li>
                <li class="breadcrumb-item active">Gérer les utilisateurs</li>
            </ol>
        </nav>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- Utilisateurs disponibles -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Utilisateurs Disponibles</h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('entreprises.attach-users', $entreprise->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="mb-3">
                            <input type="text" id="searchAvailable" class="form-control"
                                   placeholder="Rechercher un utilisateur...">
                        </div>

                        <?php if($availableUsers->where('entreprise_id', null)->count() > 0): ?>
                            <div class="user-list" id="availableUsersList" style="max-height: 500px; overflow-y: auto;">
                                <?php $__currentLoopData = $availableUsers->where('entreprise_id', null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="form-check user-item mb-2 p-2 border rounded">
                                        <input class="form-check-input" type="checkbox"
                                               name="user_ids[]" value="<?php echo e($user->id); ?>"
                                               id="user_<?php echo e($user->id); ?>">
                                        <label class="form-check-label w-100" for="user_<?php echo e($user->id); ?>">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong><?php echo e($user->nom); ?></strong><br>
                                                    <small class="text-muted">
                                                        <?php echo e($user->username); ?> | <?php echo e($user->matricule); ?>

                                                    </small>
                                                </div>
                                                <?php if($user->type == 0): ?>
                                                    <span class="badge bg-danger">Admin</span>
                                                <?php elseif($user->type == 2): ?>
                                                    <span class="badge bg-info">Support</span>
                                                <?php else: ?>
                                                    <span class="badge bg-primary">Employé</span>
                                                <?php endif; ?>
                                            </div>
                                        </label>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="mt-3">
                                <button type="button" id="selectAll" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-check-square"></i> Tout sélectionner
                                </button>
                                <button type="button" id="deselectAll" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-square"></i> Tout désélectionner
                                </button>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus-circle"></i> Ajouter les utilisateurs sélectionnés
                                </button>
                            </div>
                        <?php else: ?>
                            <p class="text-center text-muted py-4">
                                Aucun utilisateur disponible. Tous les utilisateurs sont déjà assignés à une entreprise.
                            </p>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>

        <!-- Utilisateurs de l'entreprise -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Utilisateurs de l'Entreprise</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchEntreprise" class="form-control"
                               placeholder="Rechercher un utilisateur...">
                    </div>

                    <?php if($entrepriseUsers->count() > 0): ?>
                        <div class="user-list" id="entrepriseUsersList" style="max-height: 500px; overflow-y: auto;">
                            <?php $__currentLoopData = $entrepriseUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="user-item mb-2 p-2 border rounded bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong><?php echo e($user->nom); ?></strong><br>
                                            <small class="text-muted">
                                                <?php echo e($user->username); ?> | <?php echo e($user->matricule); ?>

                                            </small>
                                        </div>
                                        <div>
                                            <?php if($user->type == 0): ?>
                                                <span class="badge bg-danger me-2">Admin</span>
                                            <?php elseif($user->type == 2): ?>
                                                <span class="badge bg-info me-2">Support</span>
                                            <?php else: ?>
                                                <span class="badge bg-primary me-2">Employé</span>
                                            <?php endif; ?>
                                            <form action="<?php echo e(route('entreprises.detach-user', [$entreprise->id, $user->id])); ?>"
                                                  method="POST"
                                                  onsubmit="return confirm('Retirer cet utilisateur de l\'entreprise ?');"
                                                  style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted py-4">
                            Aucun utilisateur dans cette entreprise.
                        </p>
                    <?php endif; ?>

                    <div class="mt-3 text-center">
                        <div class="alert alert-info">
                            <strong><?php echo e($entrepriseUsers->count()); ?></strong> utilisateur(s) dans l'entreprise
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="<?php echo e(route('entreprises.show', $entreprise->id)); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux détails
        </a>
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

    .user-item {
        transition: all 0.3s ease;
    }

    .user-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .form-check-input {
        cursor: pointer;
        width: 20px;
        height: 20px;
    }

    .form-check-label {
        cursor: pointer;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche dans les utilisateurs disponibles
    const searchAvailable = document.getElementById('searchAvailable');
    const availableUsersList = document.getElementById('availableUsersList');

    if (searchAvailable && availableUsersList) {
        searchAvailable.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = availableUsersList.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Recherche dans les utilisateurs de l'entreprise
    const searchEntreprise = document.getElementById('searchEntreprise');
    const entrepriseUsersList = document.getElementById('entrepriseUsersList');

    if (searchEntreprise && entrepriseUsersList) {
        searchEntreprise.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = entrepriseUsersList.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Sélectionner tous les utilisateurs
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.closest('.user-item').style.display !== 'none') {
                    checkbox.checked = true;
                }
            });
        });
    }

    // Désélectionner tous les utilisateurs
    const deselectAll = document.getElementById('deselectAll');
    if (deselectAll) {
        deselectAll.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/entreprises/add-users.blade.php ENDPATH**/ ?>