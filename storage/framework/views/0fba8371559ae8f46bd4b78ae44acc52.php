  
<?php $__env->startSection('title', 'Profil - GIDA'); ?>  
<?php $__env->startSection('content'); ?>
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Mon profil</h1>
      <button class="gida-btn" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">← Retour au tableau de bord</button>
    </div>

    <!-- PROFIL UTILISATEUR -->
    <section class="gida-profile" id="section-profil" aria-label="Profil utilisateur">
      <div class="profile-container">
        <div class="profile-card">
          <!-- Côté gauche - Photo et actions -->
          <div class="profile-sidebar">
            <div class="profile-photo-container">
              <div class="profile-photo">
                <img src="<?php echo e($user->photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nom).'&size=200&background=007bff&color=fff'); ?>"
                      alt="Photo de profil de <?php echo e($user->nom); ?>"
                      class="profile-img">
                <div class="photo-overlay">
                  <i class="fas fa-camera"></i>
                </div>
              </div>
            </div>
                        
            <div class="profile-actions">
              <button class="gida-btn gida-btn-primary" id="btn-edit-profile">
                <i class="fas fa-edit"></i> Modifier le profil
              </button>
              <button class="gida-btn gida-btn-secondary" onclick="window.location.href='<?php echo e(route('dashboardEmployer')); ?>'">
                <i class="fas fa-arrow-left"></i> Retour
              </button>
            </div>
                        
            <div class="profile-stats">
              <h3>Activité</h3>
              <div class="stats-grid">
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-tasks"></i>
                  </div>
                  <div class="stat-info">
                    <span class="stat-number"><?php echo e($demandes->count()); ?></span>
                    <span class="stat-label">Demandes totales</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                  </div>
                  <div class="stat-info">
                    <span class="stat-number"><?php echo e($demandes->where('statut', 'clôturé')->count()); ?></span>
                    <span class="stat-label">Demandes résolues</span>
                  </div>
                </div>
                <div class="stat-item">
                  <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                  </div>
                  <div class="stat-info">
                    <span class="stat-number"><?php echo e($demandes->where('statut', 'en cours')->count()); ?></span>
                    <span class="stat-label">En cours</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
                    
          <!-- Côté droit - Informations détaillées -->
          <div class="profile-content">
            <div class="profile-header">
              <h2><?php echo e($user->nom); ?></h2>
              <p>Membre depuis <?php echo e($user->created_at->format('F Y')); ?></p>
            </div>
                        
            <div class="info-section">
              <h3><i class="fas fa-user-circle"></i> Informations personnelles</h3>
              <div class="info-grid">
                <div class="info-item">
                  <label>Nom complet</label>
                  <p><?php echo e($user->nom); ?></p>
                </div>
                      
                <div class="info-item">
                  <label>Email</label>
                  <p><?php echo e($user->email); ?></p>
                </div>
                <div class="info-item">
                  <label>Société</label>
                  <p><?php echo e($user->societe); ?></p>
                </div>
                           
                <div class="info-section">
              <h3><i class="fas fa-cog"></i> Préférences</h3>
              <div class="info-grid">
                <div class="info-item">
                  <label>Notifications</label>
                  <p>
                    <?php if($user->notifications_preference == 'email'): ?>
                      <i class="fas fa-envelope"></i> Email
                    <?php elseif($user->notifications_preference == 'sms'): ?>
                      <i class="fas fa-sms"></i> SMS
                    <?php else: ?>
                      <i class="fas fa-bell"></i> Push
                    <?php endif; ?>
                  </p>
                </div>
                <div class="info-item">
                  <label>Accessibilité</label>
                  <p>
                    <?php if($user->accessibility_mode == 'standard'): ?>
                      Standard
                    <?php elseif($user->accessibility_mode == 'high-contrast'): ?>
                      Contraste élevé
                    <?php else: ?>
                      Mode sombre
                    <?php endif; ?>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modale d'édition -->
    <div class="modal" id="editProfileModal">
      <div class="modal-content">
        <div class="modal-header">
          <h2>Modifier le profil</h2>
          <span class="close">&times;</span>
        </div>
        <div class="modal-body">
          <form class="profile-form" method="POST" action="<?php echo e(route('profile.update')); ?>">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
                        
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="profil-nom" class="form-label">Nom complet</label>
                  <input type="text" id="profil-nom" name="nom" value="<?php echo e($user->nom); ?>" class="form-control" required>
                </div>
              </div>
                            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="profil-username" class="form-label">Nom d'utilisateur</label>
                  <input type="text" id="profil-username" name="username" value="<?php echo e($user->username); ?>" class="form-control" required>
                </div>
              </div>
            </div>
                        
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="profil-email" class="form-label">Email</label>
                  <input type="email" id="profil-email" name="email" value="<?php echo e($user->email); ?>" class="form-control" required>
                </div>
              </div>
                            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="profil-code-entreprise" class="form-label">Code entreprise</label>
                  <input type="text" id="profil-code-entreprise" name="code_entreprise" value="<?php echo e($user->code_entreprise); ?>" class="form-control" required>
                </div>
              </div>
            </div>
                        
            <div class="row mb-4">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="notif" class="form-label">Préférences notifications</label>
                  <select id="notif" name="notifications_preference" class="form-select">
                    <option value="email" <?php echo e($user->notifications_preference == 'email' ? 'selected' : ''); ?>>Email</option>
                    <option value="sms" <?php echo e($user->notifications_preference == 'sms' ? 'selected' : ''); ?>>SMS</option>
                    <option value="push" <?php echo e($user->notifications_preference == 'push' ? 'selected' : ''); ?>>Push</option>
                  </select>
                </div>
              </div>
                            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="access" class="form-label">Accessibilité</label>
                  <select id="access" name="accessibility_mode" class="form-select">
                    <option value="standard" <?php echo e($user->accessibility_mode == 'standard' ? 'selected' : ''); ?>>Standard</option>
                    <option value="high-contrast" <?php echo e($user->accessibility_mode == 'high-contrast' ? 'selected' : ''); ?>>Contraste élevé</option>
                    <option value="dark-mode" <?php echo e($user->accessibility_mode == 'dark-mode' ? 'selected' : ''); ?>>Mode sombre</option>
                  </select>
                </div>
              </div>
            </div>
                        
            <div class="form-actions d-flex gap-2 justify-content-end">
              <button type="button" class="btn btn-secondary" id="cancel-edit">Annuler</button>
              <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <style>
      /* Structure principale */
      .profile-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 1rem;
      }
            
      .profile-card {
        display: flex;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
        overflow: hidden;
      }
            
      /* Sidebar avec photo */
      .profile-sidebar {
        width: 300px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
      }
            
      .profile-photo-container {
        margin-bottom: 2rem;
      }
            
      .profile-photo {
        position: relative;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        border: 5px solid white;
      }
            
      .profile-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
            
      .photo-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 123, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
        opacity: 0;
        transition: opacity 0.3s ease;
        cursor: pointer;
      }
            
      .profile-photo:hover .photo-overlay {
        opacity: 1;
      }
            
      .photo-overlay i {
        font-size: 2rem;
      }
            
      .profile-actions {
        width: 100%;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 2rem;
      }
            
      .profile-actions .gida-btn {
        width: 100%;
        justify-content: center;
      }
            
      /* Contenu principal */
      .profile-content {
        flex: 1;
        padding: 2.5rem;
      }
            
      .profile-header {
        margin-bottom: 2.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #eaeaea;
      }
            
      .profile-header h2 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.8rem;
      }
            
      .profile-header p {
        margin: 0;
        color: #6c757d;
        font-size: 0.95rem;
      }
            
      .info-section {
        margin-bottom: 2.5rem;
      }
            
      .info-section h3 {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin: 0 0 1.5rem 0;
        color: #333;
        font-size: 1.3rem;
      }
            
      .info-section h3 i {
        color: #007bff;
      }
            
      .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
      }
            
      .info-item {
        padding: 1.25rem;
        background: #f9f9f9;
        border-radius: 8px;
        border-left: 4px solid #007bff;
      }
            
      .info-item label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
      }
            
      .info-item p {
        margin: 0;
        color: #333;
        font-size: 1.05rem;
      }
            
      /* Statistiques */
      .profile-stats {
        width: 100%;
      }
            
      .profile-stats h3 {
        margin: 0 0 1.25rem 0;
        color: #495057;
        font-size: 1.1rem;
        text-align: center;
      }
            
      .stats-grid {
        display: flex;
        flex-direction: column;
        gap: 1rem;
      }
            
      .stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      }
            
      .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9f5ff;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #007bff;
      }
            
      .stat-info {
        flex: 1;
      }
            
      .stat-number {
        display: block;
        font-size: 1.4rem;
        font-weight: bold;
        color: #007bff;
        line-height: 1.2;
      }
            
      .stat-label {
        display: block;
        color: #6c757d;
        font-size: 0.85rem;
      }
            
      /* Modale */
      .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(2px);
      }
            
      .modal-content {
        background-color: white;
        border-radius: 12px;
        width: 90%;
        max-width: 700px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
      }
            
      .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #e0e0e0;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
      }
            
      .modal-header h2 {
        margin: 0;
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
      }
            
      .close {
        color: #6c757d;
        font-size: 1.75rem;
        font-weight: bold;
        cursor: pointer;
        transition: color 0.2s ease;
        line-height: 1;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
      }
            
      .close:hover {
        color: #2c3e50;
        background-color: rgba(0, 0, 0, 0.05);
      }
            
      .modal-body {
        padding: 2rem;
        background-color: #fff;
      }
            
      /* Animation d'ouverture/fermeture du modal */
      @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
      }
            
      .modal.show {
        display: flex;
        animation: modalFadeIn 0.3s ease-out;
      }
            
      /* Responsive */
      @media (max-width: 900px) {
        .profile-card {
          flex-direction: column;
        }
                
        .profile-sidebar {
          width: 100%;
          padding: 1.5rem;
        }
                
        .info-grid {
          grid-template-columns: 1fr;
        }
      }
            
      @media (max-width: 576px) {
        .profile-content {
          padding: 1.5rem;
        }
                
        .modal-body {
          padding: 1.5rem;
        }
      }
    </style>

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la modale
        const modal = document.getElementById('editProfileModal');
        const btnEdit = document.getElementById('btn-edit-profile');
        const spanClose = document.querySelector('.close');
        const btnCancel = document.getElementById('cancel-edit');
                
        btnEdit.addEventListener('click', function() {
          modal.style.display = 'flex';
        });
                
        spanClose.addEventListener('click', function() {
          modal.style.display = 'none';
        });
                
        btnCancel.addEventListener('click', function() {
          modal.style.display = 'none';
        });
                
        window.addEventListener('click', function(event) {
          if (event.target == modal) {
            modal.style.display = 'none';
          }
        });
      });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appEmployer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/components/employer/Profile.blade.php ENDPATH**/ ?>