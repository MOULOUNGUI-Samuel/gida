

<?php $__env->startSection('content'); ?>
    <div class="admin-header">
        <h1>Historique des demandes traitées</h1>
    </div>

    <section>
        <?php if(isset($treated) && $treated->count() > 0): ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Origine</th>
                        <th>Priorité</th>
                        <th>Workflow</th>
                        <th>Date clôture</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $treated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($demande->id); ?></td>
                        <td><?php echo e($demande->titre); ?></td>
                        <td><?php echo e($demande->nom ?? ($demande->user->nom ?? '—')); ?></td>
                        <td><?php echo e($demande->priorite); ?></td>
                        <td><?php echo e($demande->workflow_label ?? $demande->workflow_status); ?></td>
                        <td><?php echo e(optional($demande->date_fermeture)->format('d/m/Y H:i') ?? '—'); ?></td>
                        <td>
                            <a class="admin-btn" href="<?php echo e(route('demandes.show', $demande->id)); ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-demandes">
                <p>Aucune demande traitée par votre société pour le moment.</p>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/supportEntreprise/historique.blade.php ENDPATH**/ ?>