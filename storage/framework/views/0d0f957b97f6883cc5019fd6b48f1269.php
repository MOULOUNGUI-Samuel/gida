<?php $__env->startSection('title', 'Modifier le Profil - Support GIDA'); ?>

<?php $__env->startSection('content'); ?>
<div class="support-profile-edit">
    <h1>Modifier le Profil</h1>

    <div class="profile-card">
        <form action="<?php echo e(route('supportEntreprise.profil.update')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('POST'); ?>

            <div class="form-section">
                <h3>Photo de profil</h3>
                <div class="photo-upload">
                    <div class="current-photo">
                        <?php if($user->photo): ?>
                            <img src="<?php echo e(asset('storage/' . $user->photo)); ?>" alt="Photo de profil actuelle">
                        <?php else: ?>
                            <div class="initials"><?php echo e(strtoupper(substr($user->nom, 0, 2))); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="upload-controls">
                        <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
                        <small class="form-text text-muted">Format accepté : JPG, PNG. Taille max : 2 Mo</small>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <h3>Informations personnelles</h3>
                
                <div class="form-group">
                    <label for="nom">Nom complet</label>
                    <input type="text" name="nom" id="nom" class="form-control <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('nom', $user->nom)); ?>" required>
                    <?php $__errorArgs = ['nom'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('email', $user->email)); ?>" required>
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="fonction">Fonction</label>
                    <input type="text" name="fonction" id="fonction" class="form-control <?php $__errorArgs = ['fonction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                           value="<?php echo e(old('fonction', $user->fonction)); ?>">
                    <?php $__errorArgs = ['fonction'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label>Société</label>
                    <input type="text" class="form-control" value="<?php echo e($user->entreprise->nom ?? $user->societe); ?>" disabled>
                    <small class="form-text text-muted">La société ne peut pas être modifiée</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                <a href="<?php echo e(route('supportEntreprise.profil')); ?>" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>

<style>
    .support-profile-edit {
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

    .form-section {
        margin-bottom: 2rem;
    }

    .photo-upload {
        display: flex;
        align-items: start;
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .current-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        overflow: hidden;
        background: #0066cc;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .current-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .initials {
        color: white;
        font-size: 2rem;
        font-weight: bold;
    }

    .upload-controls {
        flex: 1;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
        color: #333;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 5px;
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
        text-decoration: none;
    }

    .btn:hover {
        opacity: 0.9;
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/supportEntreprise/edit-profil.blade.php ENDPATH**/ ?>