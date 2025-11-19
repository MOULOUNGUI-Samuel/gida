<?php $__env->startSection('title', 'Dashboard Administrateur - GIDA'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Header -->
    <div class="admin-header">
        <h1>Dashboard Administrateur</h1>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <!-- Statistiques des demandes -->
        <div class="stat-card compact">
            <h4>Total demandes</h4>
            <div class="number"><?php echo e($stats['total']); ?></div>
            <div class="trend">+12% ce mois</div>
        </div>
        <div class="stat-card compact">
            <h4>En cours</h4>
            <div class="number"><?php echo e($stats['en_cours']); ?></div>
            <div class="trend">+5% cette semaine</div>
        </div>
        <div class="stat-card compact">
            <h4>À risque</h4>
            <div class="number"><?php echo e($stats['a_risque']); ?></div>
            <div class="trend" style="color: #dc3545;">+2 aujourd'hui</div>
        </div>
        <div class="stat-card compact">
            <h4>Clôturées</h4>
            <div class="number"><?php echo e($stats['cloturees']); ?></div>
            <div class="trend">+8% ce mois</div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="filters">
        <input type="text" id="search-input" placeholder="Rechercher une demande..." title="Tapez pour filtrer la liste des tickets">
        <select id="filter-status" title="Filtrer par statut">
            <option value="">Tous statuts</option>
            <option value="en attente">En attente</option>
            <option value="en cours">En cours</option>
            <option value="à risque">À risque</option>
            <option value="clôturé">Clôturé</option>
        </select>
        <select id="filter-company" title="Filtrer par société demandeur">
            <option value="">Toutes sociétés demandeur</option>
            <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($nom); ?>"><?php echo e($nom); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <select id="filter-assigned-company" title="Filtrer par société affectée">
            <option value="">Toutes sociétés affectées</option>
            <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($nom); ?>"><?php echo e($nom); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <option value="non-assignee">Non affectées</option>
        </select>
    </div>
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Reference</th>
                <th>Titre</th>
                <th>Demandeur</th>
                <th>Société demandeur</th>
                <th>Société affectée</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $demandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $demande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr data-id="<?php echo e($demande->id); ?>">
               
                <td><?php echo e($demande->reference); ?></td>
                    <td><?php echo e($demande->titre); ?></td>
                    <td><?php echo e($demande->user->nom ?? 'N/A'); ?></td>
                    <td><?php echo e($demande->societe ?? 'Non spécifiée'); ?></td>
                    <td>
                        <?php if($demande->societe_assignee): ?>
                            <span class="assigned-company"><?php echo e($demande->societe_assignee); ?></span>
                        <?php else: ?>
                            <span class="not-assigned">Non affectée</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="priority-<?php echo e(strtolower($demande->priorite)); ?>">
                            <?php echo e($demande->priorite); ?>

                        </span>
                    </td>
                    <td>
                        <span class="status-badge status-<?php echo e($demande->status_class); ?>">
                            <?php echo e($demande->statut); ?>

                        </span>
                    </td>
                    <td>
                        <button class="admin-btn" onclick="viewDemande(<?php echo e($demande->id); ?>)">Voir</button>
                        <button class="admin-btn secondary" onclick="assignDemande(<?php echo e($demande->id); ?>)">Affecter</button>
                        <!-- Modal de Qualification/Affectation
                         <button 
                        class="btn btn-danger btn-sm" 
                        onclick="deleteDemande(<?php echo e($demande->id); ?>)">
                        🗑 Supprimer
                    </button> -->
                        
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Modal de Qualification/Affectation -->
    <div id="qualificationModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Qualification et Affectation</h5>
                    <button type="button" class="close" onclick="closeModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="ticket-info">
                        <h6>Détails du ticket #<span id="ticket-id"></span></h6>
                        <p><strong>Titre :</strong> <span id="ticket-titre"></span></p>
                        <p><strong>Description :</strong> <span id="ticket-description"></span></p>
                        <p><strong>Date de création :</strong> <span id="ticket-date"></span></p>
                    </div>
                    <hr>
                    <form id="qualification-form">
                        <input type="hidden" id="demande-id" name="demande_id">
                        
                        <!-- Priorité -->
                        <div class="form-group">
                            <label for="priority-select"><strong>Priorité</strong></label>
                            <select id="priority-select" name="priorite" class="form-control">
                                <option value="normale">Normale</option>
                                <option value="urgente">Urgente</option>
                                <option value="critique">Critique</option>
                            </select>
                        </div>

                        <!-- Société assignée -->
                        <div class="form-group">
                            <label for="company-select"><strong>Société assignée</strong></label>
                            <select id="company-select" name="societe" class="form-control">
                                <option value="">Non assignée</option>
                                <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($nom); ?>"><?php echo e($nom); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <!-- Commentaires -->
                        <div class="form-group">
                            <label for="qualification-comments"><strong>Commentaires</strong></label>
                            <textarea id="qualification-comments" name="commentaire_qualification" class="form-control" rows="3" placeholder="Commentaires de qualification..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeModal()">Annuler</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveQualification()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'Affectation -->
    <div id="assignmentModal" class="modal fade" tabindex="-1" role="dialog" style="display: none;">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Affectation</h5>
                    <button type="button" class="close" onclick="closeAssignmentModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="assignment-info">
                        <h6>Affectation du ticket #<span id="assignment-ticket-id"></span></h6>
                        <p>Sélectionnez la société à laquelle affecter cette demande :</p>
                    </div>
                    <form id="assignment-form">
                        <input type="hidden" id="assignment-demande-id" name="demande_id">
                        
                        <!-- Société d'affectation -->
                        <div class="form-group">
                            <select id="assignment-company-select" name="societe" class="form-control" required>
                                <option value="">-- Sélectionner une société --</option>
                                <?php $__currentLoopData = $societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $nom): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($nom); ?>"><?php echo e($nom); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                                                    <!-- Statut -->
                        <div class="form-group">
                            <label for="assignment-status"><strong>Statut</strong></label>
                            <select id="assignment-status" name="statut" class="form-control" required>
                                <option value="en attente">En attente</option>
                                <option value="en cours">En cours</option>
                                <option value="à risque">À risque</option>
                                <option value="clôturé">Clôturé</option>
                            </select>
                        </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" onclick="closeAssignmentModal()">Annuler</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveAssignment()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Styles pour les cartes compactes */
        .stat-card.compact {
            padding: 15px 20px;
            min-height: 120px;
            max-width: 220px;
        }
        
        .stat-card.compact h4 {
            font-size: 14px;
            margin-bottom: 8px;
            color: #6c757d;
            font-weight: 600;
        }
        
        .stat-card.compact .number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .stat-card.compact .trend {
            font-size: 12px;
            color: #28a745;
            font-weight: 500;
        }
        
        /* Styles pour les priorités */
        .priority-normale {
            color: #28a745;
            font-weight: bold;
        }
        
        .priority-urgente {
            color: #ffc107;
            font-weight: bold;
        }
        
        .priority-critique {
            color: #dc3545;
            font-weight: bold;
        }

        /* Styles pour les sociétés affectées */
        .assigned-company {
            background: #28a745;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .not-assigned {
            background: #6c757d;
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        /* Amélioration de la grille des statistiques */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Styles pour le modal de qualification compact */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
        }

        .modal-dialog.modal-sm {
            position: relative;
            width: auto;
            margin: 50px auto;
            max-width: 400px;
        }

        .modal-content {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .modal-header {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 16px;
        }

        .close {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #6c757d;
        }

        .modal-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #2c3e50;
            font-size: 14px;
        }

        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
        }

        select.form-control {
            cursor: pointer;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 60px;
        }

        .modal-footer {
            padding: 15px 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .admin-btn.secondary {
            background: #6c757d;
        }
        
        .admin-btn.secondary:hover {
            background: #545b62;
        }

        .admin-btn.quality {
            background: #17a2b8;
        }
        
        .admin-btn.quality:hover {
            background: #138496;
        }
    </style>

    <script>
        function saveAssignment() {
            const form = document.getElementById('assignment-form');
            const formData = new FormData(form);
            const societe = formData.get('societe');
            const statut = formData.get('statut'); // Get status from form data instead of direct element
            const demandeId = formData.get('demande_id');

            if (!societe) {
                alert('Veuillez sélectionner une société');
                return;
            }

            if (!statut) {
                alert('Veuillez sélectionner un statut');
                return;
            }

            // Show loading state
            const saveBtn = document.querySelector('#assignmentModal .btn-primary');
            const originalBtnText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enregistrement...';

            fetch('/admin/assign-demande', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    demande_id: demandeId,
                    societe: societe,
                    statut: statut
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mise à jour de l'interface sans rechargement
                    const row = document.querySelector(`tr[data-id="${demandeId}"]`);
                    if (row) {
                        // Mettre à jour la société affectée
                        const companyCell = row.querySelector('.assigned-company, .not-assigned');
                        if (companyCell) {
                            companyCell.className = 'assigned-company';
                            companyCell.textContent = societe;
                        }
                        
                        // Mettre à jour le statut
                        const statusBadge = row.querySelector('.status-badge');
                        if (statusBadge) {
                            statusBadge.className = `status-badge status-${statut.toLowerCase().replace(' ', '-')}`;
                            statusBadge.textContent = statut.charAt(0).toUpperCase() + statut.slice(1);
                        }
                    }
                    
                    // Fermer le modal
                    closeAssignmentModal();
                    
                    // Afficher une notification de succès
                    alert('Affectation enregistrée avec succès');
                } else {
                    throw new Error(data.message || 'Erreur inconnue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert(`Erreur lors de l'affectation: ${error.message}`);
            })
            .finally(() => {
                // Restaurer le bouton
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;
            });
        }

        // Variables globales pour le modal
        let currentDemandeData = null;

        // Fonction pour ouvrir le modal de qualification
        function viewDemande(id) {
            // Récupérer les données complètes via AJAX
            fetch(`/admin/demande/${id}/details`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const demande = data.data;
                    
                    // Remplir les détails du ticket
                    document.getElementById('ticket-id').textContent = demande.id;
                    document.getElementById('ticket-titre').textContent = demande.titre;
                    document.getElementById('ticket-description').textContent = demande.description;
                    document.getElementById('ticket-date').textContent = new Date(demande.created_at).toLocaleString();
                    document.getElementById('demande-id').value = demande.id;

                    // Stocker les données pour la pré-sélection
                    currentDemandeData = {
                        id: demande.id,
                        titre: demande.titre,
                        societe: demande.societe,
                        societe_assignee: demande.societe_assignee,
                        priorite: demande.priorite ? demande.priorite.toLowerCase() : 'normale',
                        statut: demande.statut
                    };

                    // Pré-sélectionner la priorité actuelle dans le select
                    const prioritySelect = document.getElementById('priority-select');
                    let priorityValue = '';
                    switch(currentDemandeData.priorite) {
                        case 'normale':
                        case 'normal':
                        case 'basse':
                            priorityValue = 'normale';
                            break;
                        case 'urgente':
                        case 'urgent':
                        case 'haute':
                            priorityValue = 'urgente';
                            break;
                        case 'critique':
                        case 'critical':
                            priorityValue = 'critique';
                            break;
                        default:
                            priorityValue = 'normale';
                    }
                    prioritySelect.value = priorityValue;

                    // Pré-sélectionner la société affectée actuelle
                    const companySelect = document.getElementById('company-select');
                    if (currentDemandeData.societe_assignee) {
                        companySelect.value = currentDemandeData.societe_assignee;
                    }

                    // Afficher le modal
                    document.getElementById('qualificationModal').style.display = 'block';
                } else {
                    alert('Erreur lors du chargement des détails : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors du chargement des détails du ticket');
            });
        }

        // Fonction pour ouvrir le modal d'affectation
        function assignDemande(id) {
            document.getElementById('assignment-ticket-id').textContent = id;
            document.getElementById('assignment-demande-id').value = id;
            
            // Réinitialiser le select
            document.getElementById('assignment-company-select').value = '';
            
            // Afficher le modal
            document.getElementById('assignmentModal').style.display = 'block';
        }

        // Fonction pour fermer le modal d'affectation
        function closeAssignmentModal() {
            document.getElementById('assignmentModal').style.display = 'none';
            document.getElementById('assignment-form').reset();
        }



        // Fonction pour fermer le modal
        function closeModal() {
            document.getElementById('qualificationModal').style.display = 'none';
            // Réinitialiser le formulaire
            document.getElementById('qualification-form').reset();
        }

        // Gestion des modals
        document.addEventListener('DOMContentLoaded', function() {
            // Fermer le modal de qualification en cliquant à l'extérieur
            document.getElementById('qualificationModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });

            // Fermer le modal d'affectation en cliquant à l'extérieur
            document.getElementById('assignmentModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAssignmentModal();
                }
            });
        });

        // Fonction pour sauvegarder la qualification
        function saveQualification() {
            const formData = {
                demande_id: document.getElementById('demande-id').value,
                priorite: document.getElementById('priority-select').value,
                societe: document.getElementById('company-select').value,
                commentaire_qualification: document.getElementById('qualification-comments').value,
                workflow_status: 'analysee'
            };

            // Validation basique
            if (!formData.priorite) {
                alert('Veuillez sélectionner une priorité');
                return;
            }

            // Envoi AJAX
            fetch('/admin/save-qualification', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Qualification enregistrée avec succès !');
                    closeModal();
                    // Recharger la page pour voir les changements
                    location.reload();
                } else {
                    alert('Erreur lors de l\'enregistrement : ' + (data.message || 'Erreur inconnue'));
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'enregistrement de la qualification');
            });
        }

        function editDemande(id) {
            window.location.href = `/demandes/${id}/edit`;
        }

        // Fonction pour ouvrir le contrôle qualité
        function qualityControl(id) {
            window.location.href = `/admin/quality-control/${id}`;
        }

        // Filtrage des demandes
        document.getElementById('search-input').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.admin-table tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let found = false;
                
                // Parcourir toutes les cellules de la ligne (sauf la dernière avec les boutons)
                for (let i = 0; i < cells.length - 1; i++) {
                    if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }
                
                row.style.display = found ? '' : 'none';
            });
        });

        // Filtrage par statut
        document.getElementById('filter-status').addEventListener('change', function() {
            const status = this.value;
            const rows = document.querySelectorAll('.admin-table tbody tr');
            
            rows.forEach(row => {
                // La colonne statut est la 7ème colonne (index 6)
                const statusCell = row.querySelector('td:nth-child(7)');
                if (!status || statusCell.textContent.trim().toLowerCase().includes(status.toLowerCase())) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filtrage par société demandeur
        document.getElementById('filter-company').addEventListener('change', function() {
            const company = this.value;
            const rows = document.querySelectorAll('.admin-table tbody tr');
            
            rows.forEach(row => {
                // La colonne société demandeur est la 4ème colonne (index 3)
                const companyCell = row.querySelector('td:nth-child(4)');
                if (!company || companyCell.textContent.trim() === company) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Filtrage par société affectée
        document.getElementById('filter-assigned-company').addEventListener('change', function() {
            const assignedCompany = this.value;
            const rows = document.querySelectorAll('.admin-table tbody tr');
            
            rows.forEach(row => {
                // La colonne société affectée est la 5ème colonne (index 4)
                const assignedCompanyCell = row.querySelector('td:nth-child(5)');
                
                if (!assignedCompany) {
                    row.style.display = '';
                } else if (assignedCompany === 'non-assignee') {
                    // Afficher seulement les demandes non affectées
                    const spanElement = assignedCompanyCell.querySelector('.not-assigned');
                    row.style.display = spanElement ? '' : 'none';
                } else {
                    // Afficher seulement les demandes affectées à la société sélectionnée
                    const spanElement = assignedCompanyCell.querySelector('.assigned-company');
                    row.style.display = (spanElement && spanElement.textContent.trim() === assignedCompany) ? '' : 'none';
                }
            });
        });

        // Fonction pour appliquer tous les filtres simultanément
        function applyAllFilters() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const status = document.getElementById('filter-status').value;
            const company = document.getElementById('filter-company').value;
            const assignedCompany = document.getElementById('filter-assigned-company').value;
            
            const rows = document.querySelectorAll('.admin-table tbody tr');
            
            rows.forEach(row => {
                const cells = row.querySelectorAll('td');
                let matchesSearch = searchTerm === '';
                let matchesStatus = status === '';
                let matchesCompany = company === '';
                let matchesAssignedCompany = assignedCompany === '';
                
                // Vérifier la recherche
                if (!matchesSearch) {
                    for (let i = 0; i < cells.length - 1; i++) {
                        if (cells[i].textContent.toLowerCase().includes(searchTerm)) {
                            matchesSearch = true;
                            break;
                        }
                    }
                }
                
                // Vérifier le statut (colonne 7)
                if (!matchesStatus && status) {
                    const statusCell = cells[6];
                    matchesStatus = statusCell.textContent.trim().toLowerCase().includes(status.toLowerCase());
                }
                
                // Vérifier la société demandeur (colonne 4)
                if (!matchesCompany && company) {
                    const companyCell = cells[3];
                    matchesCompany = companyCell.textContent.trim() === company;
                }
                
                // Vérifier la société affectée (colonne 5)
                if (!matchesAssignedCompany && assignedCompany) {
                    const assignedCompanyCell = cells[4];
                    
                    if (assignedCompany === 'non-assignee') {
                        matchesAssignedCompany = assignedCompanyCell.querySelector('.not-assigned') !== null;
                    } else {
                        const spanElement = assignedCompanyCell.querySelector('.assigned-company');
                        matchesAssignedCompany = spanElement && spanElement.textContent.trim() === assignedCompany;
                    }
                }
                
                // Afficher la ligne seulement si tous les filtres correspondent
                row.style.display = (matchesSearch && matchesStatus && matchesCompany && matchesAssignedCompany) ? '' : 'none';
            });
        }

        // Ajouter les écouteurs d'événements pour tous les filtres
        document.getElementById('search-input').addEventListener('input', applyAllFilters);
        document.getElementById('filter-status').addEventListener('change', applyAllFilters);
        document.getElementById('filter-company').addEventListener('change', applyAllFilters);
        document.getElementById('filter-assigned-company').addEventListener('change', applyAllFilters);



        function deleteDemande(id) {
    if (!confirm("⚠️ Voulez-vous vraiment supprimer cette demande ?")) return;

    fetch(`/admin/demandes/${id}`, {
        method: "DELETE",
        headers: { "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Supprimer la ligne du tableau
            document.querySelector(`#demandes-table tbody tr td:first-child:contains("${id}")`)
                .closest("tr").remove();

            // ✅ Afficher un message de succès si tu as déjà l'alerte Bootstrap
            AdminDashboard?.showAlert 
                ? AdminDashboard.showAlert("🗑 Demande supprimée avec succès !") 
                : alert("Demande supprimée avec succès !");
        }
    });
}

        // Fonction pour sauvegarder l'affectation
        function saveAssignment() {
            const form = document.getElementById('assignment-form');
            const formData = new FormData(form);
            const demandeId = formData.get('demande_id');
            const societe = formData.get('societe');
            const statut = formData.get('statut');

            if (!societe) {
                alert('Veuillez sélectionner une société');
                return;
            }

            if (!statut) {
                alert('Veuillez sélectionner un statut');
                return;
            }

            // Show loading state on button
            const saveBtn = document.querySelector('#assignmentModal .btn-primary');
            const originalBtnText = saveBtn.innerHTML;
            saveBtn.disabled = true;
            saveBtn.innerHTML = 'Enregistrement...';

            // Make the API call
            fetch('/admin/assign-demande', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    demande_id: demandeId,
                    societe: societe,
                    statut: statut
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update the UI
                    const row = document.querySelector(`tr[data-id="${demandeId}"]`);
                    if (row) {
                        // Update assigned company cell
                        const companyCell = row.querySelector('td:nth-child(5)');
                        if (companyCell) {
                            let spanElement = companyCell.querySelector('.assigned-company, .not-assigned');
                            if (!spanElement) {
                                spanElement = document.createElement('span');
                                companyCell.appendChild(spanElement);
                            }
                            spanElement.className = 'assigned-company';
                            spanElement.textContent = societe;
                        }

                        // Update status cell
                        const statusCell = row.querySelector('td:nth-child(7)');
                        if (statusCell) {
                            statusCell.textContent = statut;
                        }
                    }

                    // Close modal and show success message
                    closeAssignmentModal();
                    alert('Affectation enregistrée avec succès');
                } else {
                    throw new Error(data.message || 'Erreur inconnue');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert(`Erreur lors de l'affectation: ${error.message}`);
            })
            .finally(() => {
                // Restore button state
                saveBtn.disabled = false;
                saveBtn.innerHTML = originalBtnText;
            });
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.appAdministration', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\FCI2notif\FCI2\resources\views/dashboard.blade.php ENDPATH**/ ?>