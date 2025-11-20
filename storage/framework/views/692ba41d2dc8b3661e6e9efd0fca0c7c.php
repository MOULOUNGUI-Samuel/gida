<?php $__env->startSection('title', 'Tableau de bord GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Tableau de bord GIDA</h1>
      <button class="gida-btn" id="gida-new-btn" onclick="window.location.href='<?php echo e(route('nouvelledemande')); ?>'">➕ Nouvelle demande</button>
    </div>

    <!-- DASHBOARD SYNTHÉTIQUE -->
    <section class="gida-dashboard" id="section-dashboard" aria-label="Synthèse tickets">
      <h2>Statut de mes demandes</h2>
      
      <?php if($demandes->count() > 0): ?>
        <table class="gida-table" aria-label="Tableau de tickets">
          <thead>
            <tr>
              <th>Référence</th>
              <th>Date</th>
              <th>Catégorie</th>
              <th>Priorité</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $demandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr>
                <td><?php echo e($demande->reference); ?></td>
                <td><?php echo e($demande->formatted_created_at); ?></td>
                <td><?php echo e($demande->categorie); ?></td>
                <td><?php echo e($demande->priorite); ?></td>
                <td class="status-<?php echo e($demande->statut); ?>">
                  <?php echo e(ucfirst($demande->statut)); ?>

                </td>
                <td>
                  <button class="gida-btn" onclick="window.location.href='<?php echo e(route('demandes.show', $demande->id)); ?>'">Voir</button>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      <?php else: ?>
        <div class="no-demandes">
          <p>Aucune demande trouvée. Créez votre première demande !</p>
          <button class="gida-btn" onclick="window.location.href='<?php echo e(route('nouvelledemande')); ?>'">Créer une demande</button>
        </div>
      <?php endif; ?>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appEmployer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/dashboardEmployer.blade.php ENDPATH**/ ?>