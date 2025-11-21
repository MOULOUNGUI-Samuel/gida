@extends('layouts.appAdministration')

@section('title', 'Gestion des Utilisateurs - GIDA')

@section('content')
    <div class="admin-header">
        <h1>Gestion des utilisateurs</h1>
        <button id="add-user-btn" class="admin-btn">Ajouter un utilisateur</button>
    </div>
 @include('partials.flash-messages')
    <!-- Table des utilisateurs -->
    <div class="table-responsive">
        <table class="admin-table" id="users-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Rôle</th>
                    <th>Société</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="users-body">
                <!-- Lignes utilisateurs générées dynamiquement -->
            </tbody>
        </table>
    </div>

    <!-- Modal pour ajouter/modifier un utilisateur -->
    <div id="user-modal" class="modal hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modal-title">Ajouter un utilisateur</h3>
                <button id="modal-close" class="close-btn">&times;</button>
            </div>
            <div class="modal-body" id="modal-body">
                <form id="user-form">
                    <div class="form-group">
                        <label for="user-name">Nom complet</label>
                        <input type="text" id="user-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="user-role">Rôle</label>
                        <select id="user-role" name="role" required>
                            <option value="">Sélectionner un rôle</option>
                            <option value="Administrateur">Administrateur</option>
                            <option value="Employe">Employe</option>
                            <option value="Entreprise Support">Entreprise Support</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user-company">Code Société</label>
                        <input type="text" id="user-company" name="company" required placeholder="Ex: COMKETING">
                    </div>
                    <div class="form-group">
                        <label for="user-entreprise">Entreprise</label>
                        <select id="user-entreprise" name="entreprise_id">
                            <option value="">Sélectionner une entreprise</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="user-password">Mot de passe</label>
                        <input type="password" id="user-password" name="password" minlength="6">
                        <small class="form-text">Requis pour les nouveaux utilisateurs, optionnel pour la modification</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="modal-cancel" class="admin-btn secondary">Annuler</button>
                <button id="modal-save" class="admin-btn">Enregistrer</button>
            </div>
        </div>
    </div>

    <!-- Toast notification -->
    <div id="toast" class="toast hidden">
        <span id="toast-message"></span>
    </div>
    <style>
        /* Table responsive */
        .table-responsive {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 32px;
        }

        /* Role badges */
        .role-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .role-admin {
            background: #dc3545;
            color: white;
        }
        
        .role-gestionnaire {
            background: #0f3460;
            color: white;
        }
        
        .role-support {
            background: #28a745;
            color: white;
        }

        /* Action buttons */
        .action-btn {
            padding: 6px 12px;
            margin: 0 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.85em;
            font-weight: 500;
            transition: all 0.2s;
        }

        .action-btn.edit {
            background: #0f3460;
            color: white;
        }

        .action-btn.edit:hover {
            background: #16213e;
        }

        .action-btn.delete {
            background: #dc3545;
            color: white;
        }

        .action-btn.delete:hover {
            background: #c82333;
        }

        /* Modal styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: #fff;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 20px 24px;
            border-bottom: 1px solid #e0e6ed;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            color: #1a1a2e;
            font-size: 1.4em;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #6c757d;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-btn:hover {
            color: #dc3545;
        }

        .modal-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #1a1a2e;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0f3460;
            box-shadow: 0 0 0 2px rgba(15, 52, 96, 0.1);
        }

        .form-text {
            font-size: 0.85em;
            color: #6c757d;
            margin-top: 4px;
            display: block;
        }

        .modal-footer {
            padding: 16px 24px;
            border-top: 1px solid #e0e6ed;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Toast notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 12px 20px;
            border-radius: 6px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1001;
            transition: all 0.3s;
        }

        .toast.hidden {
            display: none;
        }

        .toast.error {
            background: #dc3545;
        }

        .toast.success {
            background: #28a745;
        }
    </style>

    <script>
        // Variables globales
        let users = [];
        let entreprises = [];
        let currentEditingUser = null;

        // Éléments DOM
        const modal = document.getElementById('user-modal');
        const modalTitle = document.getElementById('modal-title');
        const modalBody = document.getElementById('modal-body');
        const modalSave = document.getElementById('modal-save');
        const modalCancel = document.getElementById('modal-cancel');
        const modalClose = document.getElementById('modal-close');
        const addUserBtn = document.getElementById('add-user-btn');
        const usersBody = document.getElementById('users-body');
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toast-message');

        // Initialisation au chargement de la page
        document.addEventListener('DOMContentLoaded', function() {
            loadUsers();
            loadEntreprises();
            initEventListeners();
        });
        
        /**
         * Initialise les écouteurs d'événements
         */
        function initEventListeners() {
            addUserBtn.addEventListener('click', () => openUserModal());
            modalCancel.addEventListener('click', closeModal);
            modalClose.addEventListener('click', closeModal);
            modalSave.addEventListener('click', saveUser);
            
            // Fermer la modal en cliquant à l'extérieur
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }
        
        /**
         * Charge les entreprises depuis le serveur
         */
        async function loadEntreprises() {
            try {
                const response = await fetch('/api/entreprises');
                if (response.ok) {
                    entreprises = await response.json();
                    populateEntreprisesSelect();
                } else {
                    console.error('Erreur lors du chargement des entreprises');
                }
            } catch (error) {
                console.error('Erreur:', error);
            }
        }

        /**
         * Remplit le sélecteur d'entreprises
         */
        function populateEntreprisesSelect() {
            const select = document.getElementById('user-entreprise');
            select.innerHTML = '<option value="">Sélectionner une entreprise</option>';
            entreprises.forEach(entreprise => {
                const option = document.createElement('option');
                option.value = entreprise.id;
                option.textContent = entreprise.nom || entreprise.societe;
                select.appendChild(option);
            });
        }

        /**
         * Charge les utilisateurs depuis le serveur
         */
        async function loadUsers() {
            try {
                const response = await fetch('/api/users');
                if (response.ok) {
                    users = await response.json();
                    renderUserTable();
                } else {
                    console.error('Erreur lors du chargement des utilisateurs');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Erreur lors du chargement des utilisateurs', 'error');
            }
        }
        
        /**
         * Rendu du tableau des utilisateurs
         */
        function renderUserTable() {
            const rows = users.map(user => {
                const roleClass = user.role.toLowerCase().replace(/[^a-z]/g, '');
                return `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td><span class="role-badge role-${roleClass}">${user.role}</span></td>
                        <td>${user.company}</td>
                        <td>
                            <button class="action-btn edit" onclick="openUserModal(${user.id})">Modifier</button>
                            <button class="action-btn delete" onclick="deleteUser(${user.id})">Supprimer</button>
                        </td>
                    </tr>
                `;
            }).join('');
            usersBody.innerHTML = rows;
        }
        
        /**
         * Ouvre la modal pour ajouter ou modifier un utilisateur
         * @param {number|null} userId - ID de l'utilisateur à modifier ou null pour ajouter
         */
        function openUserModal(userId = null) {
            currentEditingUser = userId ? users.find(u => u.id === userId) : null;

            modalTitle.textContent = currentEditingUser ? 'Modifier un utilisateur' : 'Ajouter un utilisateur';

            // Remplir les champs du formulaire
            document.getElementById('user-name').value = currentEditingUser ? currentEditingUser.name : '';
            document.getElementById('user-role').value = currentEditingUser ? currentEditingUser.role : '';
            document.getElementById('user-company').value = currentEditingUser ? currentEditingUser.company : '';
            document.getElementById('user-entreprise').value = currentEditingUser ? (currentEditingUser.entreprise_id || '') : '';
            document.getElementById('user-password').value = ''; // Toujours vider le mot de passe

            modal.classList.remove('hidden');
        }
        
        /**
         * Ferme la modal
         */
        function closeModal() {
            modal.classList.add('hidden');
            currentEditingUser = null;
            document.getElementById('user-form').reset();
        }
        
        /**
         * Sauvegarde un utilisateur (ajout ou modification)
         */
        async function saveUser() {
            const form = document.getElementById('user-form');
            const formData = new FormData(form);
            
            // Validation côté client
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Validation spécifique pour le mot de passe
            const password = formData.get('password');
            if (!currentEditingUser && !password) {
                showToast('Le mot de passe est requis pour les nouveaux utilisateurs', 'error');
                return;
            }
            
            const userData = {
                name: formData.get('name'),
                role: formData.get('role'),
                company: formData.get('company'),
                entreprise_id: formData.get('entreprise_id') || null
            };

            // Ajouter le mot de passe seulement s'il n'est pas vide
            if (password && password.trim() !== '') {
                userData.password = password;
            }

            try {
                const url = currentEditingUser ? `/api/users/${currentEditingUser.id}` : '/api/users';
                const method = currentEditingUser ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(userData)
                });

                if (response.ok) {
                    showToast('Utilisateur ' + (currentEditingUser ? 'modifié' : 'ajouté') + ' avec succès', 'success');
                    loadUsers(); // Recharger la liste complète
                    closeModal();
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Erreur lors de la sauvegarde', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Erreur de connexion', 'error');
            }
        }
        
        /**
         * Supprime un utilisateur
         * @param {number} userId - ID de l'utilisateur à supprimer
         */
        async function deleteUser(userId) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) {
                return;
            }
            
            try {
                const response = await fetch(`/api/users/${userId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    users = users.filter(u => u.id !== userId);
                    renderUserTable();
                    showToast('Utilisateur supprimé avec succès', 'success');
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Erreur lors de la suppression', 'error');
                }
            } catch (error) {
                console.error('Erreur:', error);
                showToast('Erreur de connexion', 'error');
            }
        }
        
        /**
         * Affiche un message toast
         * @param {string} message - Message à afficher
         * @param {string} type - Type de message (success, error)
         */
        function showToast(message, type = 'success') {
            toastMessage.textContent = message;
            toast.className = `toast ${type}`;
            toast.classList.remove('hidden');
            
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 3000);
        }
    </script>
@endsection
