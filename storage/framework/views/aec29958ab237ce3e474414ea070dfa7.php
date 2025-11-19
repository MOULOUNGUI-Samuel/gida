

<?php $__env->startSection('content'); ?>
    <div class="admin-header">
        <h1>Mes notifications</h1>
    </div>

    <section>
        <?php if(isset($notifications) && $notifications->count() > 0): ?>
            <div class="gida-dashboard">
                <ul class="notifications-list">
                    <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li style="padding:12px; border-bottom:1px solid #eee;">
                            <strong><?php echo e($notif->type_notification ?? 'Info'); ?>:</strong>
                            <div><?php echo e($notif->message); ?></div>
                            <small><?php echo e($notif->created_at->format('d/m/Y H:i')); ?></small>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php else: ?>
            <div class="no-demandes">
                <p>Vous n'avez aucune notification pour le moment.</p>
            </div>
        <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/supportEntreprise/notifications.blade.php ENDPATH**/ ?>