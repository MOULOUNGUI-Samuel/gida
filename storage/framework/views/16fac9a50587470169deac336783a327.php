<!-- index.html : Interface Administrateur GIDA -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>GIDA – Interface Administrateur BFEV</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Feuille de style principale -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/style.css')); ?>">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>
  <div id="login-screen">
    <div class="login-card">
      <h1>Bienvenue sur GIDA</h1>
      <p class="subtitle">Gestion Intégrée des Demandes d’Assistance – BFEV</p>

      <!-- Affichage des erreurs -->
      <?php if($errors->any()): ?>
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e($error); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>

      <!-- Formulaire de connexion -->
      <form method="POST" action="<?php echo e(route('login.post')); ?>" id="login-form" class="row g-3 app-form needs-validation" novalidate>
        <?php echo csrf_field(); ?>
        <label for="code_entreprise">Code entreprise</label>
        <input type="text" id="code_entreprise" name="code_entreprise" placeholder="code" required>

        <label for="username">Nom d’utilisateur</label>
        <input type="text" id="username" name="username" placeholder="admin" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>

        <button type="submit">Se connecter</button>
      </form>
    </div>
  </div>

  <div id="toast-container"></div>
  <script src="<?php echo e(asset('assets/app.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/auth/login.blade.php ENDPATH**/ ?>