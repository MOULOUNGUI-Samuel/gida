<?php $__env->startSection('content'); ?>
    <div class="admin-header">
        <h1>Accueil Support</h1>
    </div>

    <section class="stats-grid">
        <div class="stat-card">
            <h3>Total demandes</h3>
            <div class="number"><?php echo e($stats['total'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>En attente</h3>
            <div class="number"><?php echo e($stats['en_attente'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>En traitement</h3>
            <div class="number"><?php echo e($stats['en_traitement'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>Résolues/Validées/Clôturées</h3>
            <div class="number"><?php echo e($stats['resolues'] ?? 0); ?></div>
        </div>
        <div class="stat-card">
            <h3>À risque</h3>
            <div class="number"><?php echo e($stats['a_risque'] ?? 0); ?></div>
        </div>
    </section>

    <section>
        <?php if(isset($recent) && $recent->count() > 0): ?>
            <h2 style="margin: 12px 0 16px;">Demandes récentes</h2>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Origine</th>
                        <th>Priorité</th>
                        <th>Workflow</th>
                        <th>Créée</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $recent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($demande->id); ?></td>
                        <td><?php echo e($demande->titre); ?></td>
                        <td><?php echo e($demande->nom ?? ($demande->user->nom ?? '—')); ?></td>
                        <td><?php echo e($demande->priorite); ?></td>
                        <td><?php echo e($demande->workflow_label ?? $demande->workflow_status); ?></td>
                        <td><?php echo e($demande->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <a class="admin-btn" href="<?php echo e(route('demandes.show', $demande->id)); ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-demandes">
                <p>Aucune demande récente.</p>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/supportEntreprise/homeEntrepiseSupp.blade.php ENDPATH**/ ?>