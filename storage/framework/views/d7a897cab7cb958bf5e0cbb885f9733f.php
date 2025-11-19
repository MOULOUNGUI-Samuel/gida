

<?php $__env->startSection('title', 'Détails de la demande - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Détails de la demande #<?php echo e($demande->id); ?></h1>
      <button class="gida-btn" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">← Retour au tableau de bord</button>
    </div>

    <!-- DÉTAILS DE LA DEMANDE -->
    <section class="demande-details" id="section-details">
      <div class="details-container">
        
        <!-- Informations principales 
                   <div class="demande-meta">
            <span class="reference">Référence: TK-<?php echo e(str_pad($demande->id, 5, '0', STR_PAD_LEFT)); ?></span>
            <span class="date">Créée le: <?php echo e($demande->formatted_created_at); ?></span>
            <span class="status status-<?php echo e($demande->statut); ?>"><?php echo e(ucfirst($demande->statut)); ?></span>
          </div>-->
        <div class="demande-header">
          <h2><?php echo e($demande->titre); ?></h2>
        </div>

        <!-- Grille d'informations -->
        <div class="demande-grid">
          
          <!-- Colonne gauche -->
          <div class="demande-info">
            <h3>Informations de la demande</h3>
            
            <div class="info-group">
              <label>Catégorie:</label>
              <span><?php echo e($demande->categorie); ?></span>
            </div>
            
            <div class="info-group">
              <label>Priorité:</label>
              <span class="priorite-<?php echo e(strtolower($demande->priorite)); ?>"><?php echo e($demande->priorite); ?></span>
            </div>
            
            <div class="info-group">
              <label>Date limite:</label>
              <span><?php echo e($demande->date_limite ? $demande->date_limite->format('d/m/Y') : 'Non définie'); ?></span>
            </div>
            
            <div class="info-group">
              <label>Description:</label>
              <div class="description-content">
                <?php echo e($demande->description); ?>

              </div>
            </div>
            
            <div class="info-group">
              <label>Progression du traitement:</label>
              <div class="progress-container">
                <?php
                  $statuses = [
                      'en attente' => ['icon' => '⏳', 'class' => 'status-pending'],
                      'en cours' => ['icon' => '🔄', 'class' => 'status-in-progress'],
                      'à risque' => ['icon' => '⚠️', 'class' => 'status-at-risk'],
                      'clôturé' => ['icon' => '✅', 'class' => 'status-completed']
                  ];
                  // Set default status to 'en attente' if empty
                  $currentStatus = !empty($demande->statut) ? strtolower($demande->statut) : 'en attente';
                  $currentIndex = array_search($currentStatus, array_keys($statuses));
                  $progress = $currentIndex !== false ? (($currentIndex + 1) / count($statuses)) * 100 : 0;
                ?>
                
                <div class="progress-track">
                  <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $isActive = array_search($status, array_keys($statuses)) <= $currentIndex;
                        $isCurrent = $status === $currentStatus;
                    ?>
                    <div class="progress-step <?php echo e($isActive ? 'active' : ''); ?> <?php echo e($isCurrent ? 'current' : ''); ?>">
                      <div class="step-icon <?php echo e($data['class']); ?>">
                        <?php echo e($data['icon']); ?>

                      </div>
                      <div class="step-label"><?php echo e(ucfirst($status)); ?></div>
                    </div>
                    <?php if(!$loop->last): ?>
                      <div class="progress-connector">
                        <div class="progress-line <?php echo e($isActive ? 'active' : ''); ?>" style="width: <?php echo e($isActive ? '100%' : '0%'); ?>"></div>
                      </div>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                
                <div class="progress-bar-container">
                  <div class="progress-bar">
                    <div class="progress <?php echo e($statuses[$currentStatus]['class'] ?? 'status-pending'); ?>" style="width: <?php echo e($progress); ?>%;"></div>
                  </div>
                  <div class="progress-percentage"><?php echo e(round($progress)); ?>%</div>
                </div>
                
                <div class="current-status">
                  <span class="status-badge <?php echo e($statuses[$currentStatus]['class'] ?? 'status-pending'); ?>">
                    <?php echo e($statuses[$currentStatus]['icon'] ?? '⏳'); ?> Statut actuel: <?php echo e(ucfirst($currentStatus)); ?>

                  </span>
                </div>
              </div>
            </div>
            
            <style>
            .progress-track {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 20px;
            }
            .progress-step {
              display: flex;
              flex-direction: column;
              align-items: center;
              position: relative;
              z-index: 1;
            }
            .step-icon {
              width: 40px;
              height: 40px;
              border-radius: 50%;
              display: flex;
              align-items: center;
              justify-content: center;
              margin-bottom: 5px;
              background: #f0f0f0;
              border: 2px solid #ddd;
              font-size: 18px;
              transition: all 0.3s ease;
            }
            .progress-step.active .step-icon {
              background: #e3f2fd;
              border-color: #2196f3;
              color: #0d47a1;
            }
            .progress-step.current .step-icon {
              transform: scale(1.2);
              box-shadow: 0 0 0 3px rgba(33, 150, 243, 0.3);
            }
            .step-label {
              font-size: 12px;
              text-align: center;
              color: #666;
              margin-top: 5px;
            }
            .progress-connector {
              flex: 1;
              height: 3px;
              background: #e0e0e0;
              position: relative;
              margin: 0 5px;
            }
            .progress-line {
              height: 100%;
              background: #2196f3;
              transition: width 0.5s ease;
            }
            .progress-bar-container {
              margin: 20px 0;
              position: relative;
            }
            .progress-bar {
              height: 10px;
              background: #e0e0e0;
              border-radius: 5px;
              overflow: hidden;
              margin-bottom: 10px;
            }
            .progress {
              height: 100%;
              transition: width 0.5s ease;
            }
            .progress-percentage {
              text-align: right;
              font-size: 12px;
              color: #666;
            }
            .current-status {
              margin-top: 15px;
              text-align: center;
            }
            .status-badge {
              display: inline-block;
              padding: 5px 15px;
              border-radius: 20px;
              font-weight: 500;
              font-size: 14px;
            }
            .status-pending { background-color: #fff3e0; color: #e65100; }
            .status-in-progress { background-color: #e3f2fd; color: #0d47a1; }
            .status-at-risk { background-color: #ffebee; color: #c62828; }
            .status-completed { background-color: #e8f5e9; color: #2e7d32; }
            .progress.status-pending { background: linear-gradient(90deg, #ffb74d, #ff9800); }
            .progress.status-in-progress { background: linear-gradient(90deg, #64b5f6, #1976d2); }
            .progress.status-at-risk { background: linear-gradient(90deg, #ef9a9a, #d32f2f); }
            .progress.status-completed { background: linear-gradient(90deg, #81c784, #2e7d32); }
            </style>
            
            <?php if($demande->infos_supplementaires): ?>
              <div class="info-group">
                <label>Informations supplémentaires:</label>
                <div class="description-content">
                  <?php echo e($demande->infos_supplementaires); ?>

                </div>
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Colonne droite -->
          <div class="demandeur-info">
            <h3>Informations du demandeur</h3>
            
            <div class="info-group">
              <label>Nom:</label>
              <span><?php echo e($demande->nom); ?></span>
            </div>
            
            <div class="info-group">
              <label>Société:</label>
              <div style="font-weight: 600;">
                <?php if($demande->user->societe): ?> 
                    <?php echo e($demande->user->societe); ?>

                <?php elseif($demande->user->entreprise): ?> 
                    <?php echo e($demande->user->entreprise->nom); ?>

                <?php else: ?>
                    Entreprise non définie
                <?php endif; ?>
              </div>
            </div>
            
            <div class="info-group">
              <label>Email:</label>
              <span><?php echo e($demande->mail); ?></span>
          </div>
        </div>

        <!-- Pièces jointes -->
        <?php if(($demande->piecesJointes && $demande->piecesJointes->count() > 0) || $demande->fichier): ?>
          <div class="pieces-jointes">
            <h3>Pièces jointes</h3>
            <?php if($demande->piecesJointes && $demande->piecesJointes->count() > 0): ?>
              <ul style="list-style:none; padding-left:0; margin:0;">
                <?php $__currentLoopData = $demande->piecesJointes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li class="fichier-item" style="margin-bottom:8px;">
                    <a href="#" onclick="openFileModal('<?php echo e(asset('storage/' . $pj->chemin)); ?>')" class="file-preview-link">
                      <?php echo e($pj->nom_fichier); ?>

                    </a>
                  </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
            <?php elseif($demande->fichier): ?>
              <div class="fichier-item">
                <a href="#" onclick="openFileModal('<?php echo e(asset('storage/' . $demande->fichier)); ?>')" class="file-preview-link">
                  Voir la pièce jointe
                </a>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Modal pour l'aperçu du fichier -->
          <div id="fileModal" class="modal" style="display: none;">
              <div class="modal-content" style="max-width: 90%; max-height: 90vh; margin: 5vh auto;">
                  <div class="modal-header">
                      <h5 class="modal-title">Aperçu du fichier</h5>
                      <button type="button" class="close" onclick="closeFileModal()">
                          <span>&times;</span>
                      </button>
                  </div>
                  <div class="modal-body" style="padding: 0; overflow: auto;">
                      <iframe id="fileViewer" src="" style="width: 100%; height: 80vh; border: none;"></iframe>
                  </div>
                  <div class="modal-footer">
                      <a id="downloadFile" href="" class="btn btn-primary" download style="background-color: #2563eb; border-color: #2563eb; color: white; font-weight: 500; padding: 8px 16px; border-radius: 6px; text-decoration: none; transition: background-color 0.2s;">Télécharger</a>
                      <button type="button" class="btn btn-secondary" onclick="closeFileModal()">Fermer</button>
                  </div>
              </div>
          </div>

          <script>
              function openFileModal(fileUrl) {
                  const modal = document.getElementById('fileModal');
                  const fileViewer = document.getElementById('fileViewer');
                  const downloadLink = document.getElementById('downloadFile');
                  
                  // Définir la source du fichier
                  fileViewer.src = fileUrl;
                  downloadLink.href = fileUrl;
                  
                  // Afficher le modal
                  modal.style.display = 'block';
                  
                  // Empêcher le défilement de la page principale
                  document.body.style.overflow = 'hidden';
              }
              
              function closeFileModal() {
                  const modal = document.getElementById('fileModal');
                  const fileViewer = document.getElementById('fileViewer');
                  
                  // Réinitialiser la source de l'iframe
                  fileViewer.src = '';
                  
                  // Cacher le modal
                  modal.style.display = 'none';
                  
                  // Rétablir le défilement de la page principale
                  document.body.style.overflow = 'auto';
              }
              
              // Fermer le modal en cliquant à l'extérieur
              window.onclick = function(event) {
                  const modal = document.getElementById('fileModal');
                  if (event.target === modal) {
                      closeFileModal();
                  }
              }
          </script>
          
          <style>
              .file-preview-link {
                  color: #3490dc;
                  text-decoration: none;
                  transition: color 0.2s;
              }
              
              .file-preview-link:hover {
                  color: #1d68a7;
                  text-decoration: underline;
              }
              
              .modal {
                  position: fixed;
                  top: 0;
                  left: 0;
                  width: 100%;
                  height: 100%;
                  background-color: rgba(0, 0, 0, 0.7);
                  z-index: 1050;
                  display: flex;
                  align-items: center;
                  justify-content: center;
              }
              
              .modal-content {
                  background: white;
                  border-radius: 8px;
                  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                  width: 100%;
                  max-width: 1000px;
              }
              
              .modal-header {
                  padding: 15px 20px;
                  border-bottom: 1px solid #e9ecef;
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
              }
              
              .modal-title {
                  margin: 0;
                  font-size: 1.25rem;
                  font-weight: 500;
              }
              
              .modal-footer {
                  padding: 15px 20px;
                  border-top: 1px solid #e9ecef;
                  display: flex;
                  justify-content: flex-end;
                  gap: 10px;
              }
              
              .close {
                  background: none;
                  border: none;
                  font-size: 1.5rem;
                  cursor: pointer;
                  color: #6c757d;
              }
              
              .close:hover {
                  color: #343a40;
              }
          </style>

        <!-- Actions -->
        <div class="demande-actions">
          <?php if(auth()->user()->type == 0): ?> 
            <div class="admin-actions">
              <h3>Actions administrateur</h3>
              <div class="action-buttons">
                <button class="gida-btn gida-btn-primary" onclick="window.location.href='<?php echo e(route('demandes.edit', $demande->id)); ?>'">
                  Modifier le statut
                </button>
                <button class="gida-btn gida-btn-secondary" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">
                  Retour à la liste
                </button>
              </div>
            </div>
          <?php else: ?>
            <div class="user-actions">
              <button class="gida-btn gida-btn-secondary" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">
                Retour au tableau de bord
              </button>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <script src="<?php echo e(asset('js/app.js')); ?>"></script> 
    <script>
        Echo.private(`demande.<?php echo e($demande->id); ?>`)
            .listen('DemandeUpdated', (e) => {
                document.querySelector('.status').innerText = e.statut;
                if(document.getElementById('progress-bar')){
                    let bar = document.getElementById('progress-bar');
                    bar.style.width = e.progress + '%';
                    bar.innerText = e.progress + '%';
                }
            });
    </script>

    <style>
      .details-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .demande-header {
        border-bottom: 2px solid #eee;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
      }

      .demande-header h2 { color: #333; margin-bottom: 0.5rem; }

      .demande-meta {
        display: flex; gap: 1rem; flex-wrap: wrap; font-size: 0.9rem; color: #666;
      }

      .status {
        padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: bold; font-size: 0.8rem;
      }

      .demande-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem; }

      .demande-info h3, .demandeur-info h3 { color: #333; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee; }

      .info-group { margin-bottom: 1rem; }
      .info-group label { display: block; font-weight: 600; color: #555; margin-bottom: 0.25rem; font-size: 0.9rem; }
      .info-group span { color: #333; font-size: 1rem; }
      .description-content { background: #f8f9fa; padding: 1rem; border-radius: 4px; border-left: 4px solid #007bff; white-space: pre-wrap; line-height: 1.5; }

      .priorite-normale { color: #28a745; font-weight: bold; }
      .priorite-urgente { color: #ffc107; font-weight: bold; }
      .priorite-critique { color: #dc3545; font-weight: bold; }

      .pieces-jointes { border-top: 1px solid #eee; padding-top: 1rem; margin-bottom: 2rem; }
      .pieces-jointes h3 { color: #333; margin-bottom: 1rem; }
      .fichier-item { background: #f8f9fa; padding: 1rem; border-radius: 4px; border: 1px solid #dee2e6; }
      .fichier-link { color: #007bff; text-decoration: none; font-weight: 500; }
      .fichier-link:hover { text-decoration: underline; }

      .demande-actions { border-top: 1px solid #eee; padding-top: 1rem; }
      .admin-actions h3, .user-actions h3 { color: #333; margin-bottom: 1rem; }
      .action-buttons { display: flex; gap: 1rem; flex-wrap: wrap; }
      .gida-btn-primary { background: #007bff; color: white; }
      .gida-btn-secondary { background: #6c757d; color: white; }

      @media (max-width: 768px) {
        .demande-grid { grid-template-columns: 1fr; }
        .demande-meta { flex-direction: column; gap: 0.5rem; }
      }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make(auth()->check() && auth()->user()->type == 1 ? 'layouts.appEmployer' : 'layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/demandes/show.blade.php ENDPATH**/ ?>