<?php $__env->startSection('title', 'Dashboard Administrateur - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container my-4">

        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Dashboard Administrateur</h1>
        </div>

        
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">Total demandes</h6>
                        <div class="h3 mb-1"><?php echo e($stats['total']); ?></div>
                        <small class="text-success">+12% ce mois</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">En cours</h6>
                        <div class="h3 mb-1"><?php echo e($stats['en_cours']); ?></div>
                        <small class="text-success">+5% cette semaine</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">À risque</h6>
                        <div class="h3 mb-1"><?php echo e($stats['a_risque']); ?></div>
                        <small class="text-danger">+2 aujourd'hui</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">Clôturées</h6>
                        <div class="h3 mb-1"><?php echo e($stats['cloturees']); ?></div>
                        <small class="text-success">+8% ce mois</small>
                    </div>
                </div>
            </div>
        </div>

        
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
                <label for="search-input" class="form-label">Recherche</label>
                <input type="text" id="search-input" name="q" value="<?php echo e(request('q')); ?>" class="form-control"
                    placeholder="Rechercher une demande...">
            </div>

            <div class="col-md-2">
                <label for="filter-status" class="form-label">Statut</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="en attente" <?php echo e(request('status') === 'en attente' ? 'selected' : ''); ?>>En attente
                    </option>
                    <option value="en cours" <?php echo e(request('status') === 'en cours' ? 'selected' : ''); ?>>En cours</option>
                    <option value="à risque" <?php echo e(request('status') === 'à risque' ? 'selected' : ''); ?>>À risque</option>
                    <option value="clôturé" <?php echo e(request('status') === 'clôturé' ? 'selected' : ''); ?>>Clôturé</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-company" class="form-label">Société demandeur</label>
                <select id="filter-company" name="company" class="form-select">
                    <option value="">Toutes</option>
                    <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($nom); ?>" <?php echo e(request('company') === $nom ? 'selected' : ''); ?>>
                            <?php echo e($nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-assigned-company" class="form-label">Société affectée</label>
                <select id="filter-assigned-company" name="assigned_company" class="form-select">
                    <option value="">Toutes</option>
                    <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($nom); ?>" <?php echo e(request('assigned_company') === $nom ? 'selected' : ''); ?>>
                            <?php echo e($nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <option value="non-assignee" <?php echo e(request('assigned_company') === 'non-assignee' ? 'selected' : ''); ?>>
                        Non affectées
                    </option>
                </select>
            </div>

            <div class="col-12 text-end mt-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    Appliquer les filtres
                </button>
                <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary btn-sm">
                    Réinitialiser
                </a>
            </div>
        </form>

        
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="demandes-table">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Titre</th>
                                <th>Demandeur</th>
                                <th>Société demandeur</th>
                                <th>Société affectée</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $demandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($demande->reference); ?></td>
                                    <td><?php echo e($demande->titre); ?></td>
                                    <td><?php echo e($demande->user->nom ?? 'N/A'); ?></td>
                                    <td><?php echo e($demande->societe ?? 'Non spécifiée'); ?></td>
                                    <td>
                                        <?php if($demande->societe_assignee): ?>
                                            <span class="badge bg-success">
                                                <?php echo e($demande->societe_assignee); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                Non affectée
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php $p = strtolower($demande->priorite); ?>
                                        <span
                                            class="badge
                                        <?php if($p === 'normale'): ?> bg-success
                                        <?php elseif($p === 'urgente'): ?> bg-warning text-dark
                                        <?php elseif($p === 'critique'): ?> bg-danger
                                        <?php else: ?> bg-secondary <?php endif; ?>
                                    ">
                                            <?php echo e($demande->priorite); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <?php $s = strtolower($demande->statut); ?>
                                        <span
                                            class="badge
                                        <?php if($s === 'en attente'): ?> bg-warning text-dark
                                        <?php elseif($s === 'en cours'): ?> bg-info text-dark
                                        <?php elseif($s === 'à risque'): ?> bg-danger
                                        <?php elseif($s === 'clôturé'): ?> bg-success
                                        <?php else: ?> bg-secondary <?php endif; ?>
                                    ">
                                            <?php echo e(ucfirst($demande->statut)); ?>

                                        </span>
                                    </td>
                                    <td class="text-end">
                                        
                                        <a href="<?php echo e(route('demandes.show', $demande)); ?>"
                                            class="btn btn-outline-primary btn-sm">
                                            Voir
                                        </a>

                                        
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-1"
                                            data-bs-toggle="modal" data-bs-target="#assignmentModal-<?php echo e($demande->id); ?>">
                                            Affecter
                                        </button>

                                        
                                        
                                    </td>
                                </tr>

                                
                                <div class="modal fade" id="assignmentModal-<?php echo e($demande->id); ?>" tabindex="-1"
                                    aria-labelledby="assignmentModalLabel-<?php echo e($demande->id); ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="<?php echo e(route('admin.assign-demande')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <input type="hidden" name="demande_id" value="<?php echo e($demande->id); ?>">

                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="assignmentModalLabel-<?php echo e($demande->id); ?>">
                                                        Affectation de la demande #<?php echo e($demande->reference); ?>

                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Fermer"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>Titre :</strong> <?php echo e($demande->titre); ?>

                                                        </small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Société demandeur</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            value="<?php echo e($demande->societe ?? 'Non spécifiée'); ?>" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="societe-<?php echo e($demande->id); ?>" class="form-label">
                                                            Société affectée
                                                        </label>
                                                        <select name="societe" id="societe-<?php echo e($demande->id); ?>"
                                                            class="form-select" required>
                                                            <option value="">-- Sélectionner une société --</option>
                                                            <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idSoc => $nomSoc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($nomSoc); ?>"
                                                                    <?php echo e($demande->societe_assignee === $nomSoc ? 'selected' : ''); ?>>
                                                                    <?php echo e($nomSoc); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="statut-<?php echo e($demande->id); ?>" class="form-label">
                                                            Statut
                                                        </label>
                                                        <select name="statut" id="statut-<?php echo e($demande->id); ?>"
                                                            class="form-select" required>
                                                            <option value="en attente"
                                                                <?php echo e($demande->statut === 'en attente' ? 'selected' : ''); ?>>
                                                                En attente</option>
                                                            <option value="en cours"
                                                                <?php echo e($demande->statut === 'en cours' ? 'selected' : ''); ?>>En
                                                                cours</option>
                                                            <option value="à risque"
                                                                <?php echo e($demande->statut === 'à risque' ? 'selected' : ''); ?>>À
                                                                risque</option>
                                                            <option value="clôturé"
                                                                <?php echo e($demande->statut === 'clôturé' ? 'selected' : ''); ?>>
                                                                Clôturé</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">
                                        Aucune demande trouvée.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <?php if(method_exists($demandes, 'links')): ?>
                    <div class="card-footer">
                        <?php echo e($demandes->withQueryString()->links()); ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/dashboard.blade.php ENDPATH**/ ?>