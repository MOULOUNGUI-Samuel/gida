<?php $__env->startSection('title', 'Nouvelle demande - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Nouvelle demande d'assistance</h1>
      <button class="gida-btn" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">← Retour au tableau de bord</button>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success" style="margin: 20px auto; max-width: 800px;">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger" style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px auto; max-width: 800px; border: 1px solid #f5c6cb; border-radius: 4px;">
            <ul style="margin: 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- FORMULAIRE NOUVELLE DEMANDE -->
    <section class="gida-form-section" id="section-new" aria-label="Créer une demande">
      <div class="form-container">
        <!-- En-tête avec les informations de l'utilisateur -->
        <div class="user-info-header" style="background: #f8f9fa; border-radius: 8px; padding: 20px; margin-bottom: 30px; border-left: 4px solid #2769a2;">
          <h3 style="margin-top: 0; color: #2769a2; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-user-circle" style="font-size: 1.5em;"></i>
            Informations du demandeur
          </h3>
          <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 15px; margin-top: 15px;">
            <div>
              <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;">Nom complet</div>
              <div style="font-weight: 600;"><?php echo e(auth()->user()->nom); ?></div>
            </div>
               <div>
              <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;">Société</div>
              <div style="font-weight: 600;">
                  <?php if(Auth::user()->societe): ?> 
                      <?php echo e(Auth::user()->societe); ?>

                  <?php elseif(Auth::user()->entreprise): ?> 
                      <?php echo e(Auth::user()->entreprise->nom); ?>

                  <?php else: ?>
                      Entreprise non définie
                  <?php endif; ?>
              </div>
          </div>

            <div>
              <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;">Matricule</div>
              <div style="font-weight: 600;"><?php echo e(auth()->user()->matricule); ?></div>
            </div>
            <div>
              <div style="font-size: 0.9em; color: #666; margin-bottom: 5px;">Email</div>
              <div style="font-weight: 600;"><?php echo e(auth()->user()->email ?? 'Non spécifié'); ?></div>
            </div>
          </div>
        </div>
        
        <form class="gida-form" method="POST" action="<?php echo e(route('demandes.store')); ?>" enctype="multipart/form-data">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="nom" value="<?php echo e(auth()->user()->nom); ?>">
          <input type="hidden" name="code_entreprise" value="<?php echo e(auth()->user()->code_entreprise); ?>">
          <input type="hidden" name="matricule" value="<?php echo e(auth()->user()->matricule); ?>">
          <input type="hidden" name="mail" value="<?php echo e(auth()->user()->email); ?>">
          <input type="hidden" name="fonction" value="<?php echo e(auth()->user()->fonction ?? 'Non spécifiée'); ?>">
          <input type="hidden" name="societe" value="<?php echo e(auth()->user()->entreprise->nom ?? 'Non spécifiée'); ?>">
          
          <div class="form-group">
            <label for="titre">Titre de la demande *</label>
            <input type="text" id="titre" name="titre" placeholder="Titre de votre demande" required value="<?php echo e(old('titre')); ?>">
            <?php $__errorArgs = ['titre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="categorie">Catégorie de la demande *</label>
            <select id="categorie" name="categorie" required>
              <option value="">Sélectionnez une catégorie</option>
              <option value="Informatique" <?php echo e(old('categorie') == 'Informatique' ? 'selected' : ''); ?>>Informatique</option>
              <option value="RH" <?php echo e(old('categorie') == 'RH' ? 'selected' : ''); ?>>RH</option>
              <option value="Finances" <?php echo e(old('categorie') == 'Finances' ? 'selected' : ''); ?>>Finances</option>
              <option value="Juridique" <?php echo e(old('categorie') == 'Juridique' ? 'selected' : ''); ?>>Juridique</option>
              <option value="Logistique" <?php echo e(old('categorie') == 'Logistique' ? 'selected' : ''); ?>>Logistique</option>
              <option value="Marketing" <?php echo e(old('categorie') == 'Marketing' ? 'selected' : ''); ?>>Marketing</option>
              <option value="Autre" <?php echo e(old('categorie') == 'Autre' ? 'selected' : ''); ?>>Autre</option>
            </select>
            <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="priorite">Priorité *</label>
            <select id="priorite" name="priorite" required>
              <option value="">Sélectionnez une priorité</option>
              <option value="Normale" <?php echo e(old('priorite') == 'Normale' ? 'selected' : ''); ?>>Normale</option>
              <option value="Urgente" <?php echo e(old('priorite') == 'Urgente' ? 'selected' : ''); ?>>Urgente</option>
              <option value="Critique" <?php echo e(old('priorite') == 'Critique' ? 'selected' : ''); ?>>Critique</option>
            </select>
            <?php $__errorArgs = ['priorite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="date_limite">Date limite souhaitée</label>
            <?php
                $defaultDate = old('date_limite') ? \Carbon\Carbon::parse(old('date_limite'))->format('Y-m-d') : '';
            ?>
            <input 
                type="date" 
                id="date_limite" 
                name="date_limite" 
                value="<?php echo e($defaultDate); ?>"
                min="<?php echo e(now()->format('Y-m-d')); ?>"
                class="form-control"
            >
            <?php $__errorArgs = ['date_limite'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>


          <div class="form-group">
            <label for="description">Description détaillée *</label>
            <textarea id="description" name="description" rows="5" placeholder="Décrivez votre demande en détail..." required><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="fichier">Pièce jointe (optionnel)</label>
            <div class="file-upload">
              <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.html">
              <small class="file-hint">Formats acceptés : PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, HTML (max 10Mo)</small>
            </div>
            <?php $__errorArgs = ['fichier'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-group">
            <label for="fichiers">Autres pièces jointes (multiples)</label>
            <div class="file-upload">
              <input type="file" id="fichiers" name="fichiers[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.html,.htm">
              <small class="file-hint">Vous pouvez sélectionner plusieurs fichiers (max 10Mo chacun)</small>
            </div>
            <?php $__errorArgs = ['fichiers.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <span class="error"><?php echo e($message); ?></span>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>

          <div class="form-actions">
            <button class="gida-btn gida-btn-primary" type="submit">envoyer</button>
            <button class="gida-btn gida-btn-secondary" type="button" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">Annuler</button>
          </div>
        </form>
      </div>
    </section>

    <style>
      /* File upload styles */
      .file-upload {
        margin-top: 8px;
      }
      
      .file-upload input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px dashed #ccc;
        border-radius: 4px;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
      }
      
      .file-upload input[type="file"]:hover {
        border-color: #2769a2;
        background-color: #f0f7ff;
      }
      
      .file-hint {
        display: block;
        margin-top: 5px;
        color: #6c757d;
        font-size: 0.85em;
      }
      
      .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .form-group {
        margin-bottom: 1.5rem;
      }

      .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
      }

      .form-group textarea {
        resize: vertical;
        min-height: 100px;
      }

      .form-group small {
        display: block;
        margin-top: 0.25rem;
        color: #666;
        font-size: 0.875rem;
      }

      .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
      }

      .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
      }

      .gida-btn-primary {
        background: #007bff;
        color: white;
      }

      .gida-btn-secondary {
        background: #6c757d;
        color: white;
      }

      .gida-btn:hover {
        opacity: 0.9;
      }

      .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
      }

      .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
      }

      .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
      }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appEmployer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Gida\resources\views/components/employer/Nouvelledemande.blade.php ENDPATH**/ ?>