/* app.js : Logique de l'interface administrateur GIDA
 * Ce fichier contient toutes les fonctions nécessaires pour faire fonctionner
 * l'application de manière autonome côté frontend. Aucune connexion à un
 * serveur n'est requise : les données sont simulées en mémoire.
 */

// --- Données simulées ---

// Liste de sociétés supports disponibles pour l'affectation
const supportCompanies = [
  "COMKETING",
  "YOD INGÉNIERIE",
  "FCI",
  "ALPHON CONSULTING"
];



// Exemple d'utilisateurs administratifs
let users = [
  { id: 1, name: "Administrateur Principal", role: "Admin", company: "BFEV" },
  { id: 2, name: "Agent Support 1", role: "Support", company: "COMKETING" },
  { id: 3, name: "Agent Support 2", role: "Support", company: "YOD INGÉNIERIE" },
  { id: 4, name: "Gestionnaire", role: "Gestionnaire", company: "BFEV" }
];

// --- Sélection des éléments du DOM ---
const loginScreen     = document.getElementById('login-screen');
const loginForm       = document.getElementById('login-form');
const mainContainer   = document.getElementById('main-container');
const sidebar         = document.getElementById('sidebar');
const content         = document.getElementById('content');
const modules         = document.querySelectorAll('.module');
const ticketsBody     = document.getElementById('tickets-body');
const usersBody       = document.getElementById('users-body');
const searchInput     = document.getElementById('search-input');
const filterStatus    = document.getElementById('filter-status');
const filterCompany   = document.getElementById('filter-company');
const statTotal       = document.getElementById('stat-total');
const statOpen        = document.getElementById('stat-open');
const statRisk        = document.getElementById('stat-risk');
const statClosed      = document.getElementById('stat-closed');
const modal           = document.getElementById('modal');
const modalTitle      = document.getElementById('modal-title');
const modalBody       = document.getElementById('modal-body');
const modalCancel     = document.getElementById('modal-cancel');
const modalSave       = document.getElementById('modal-save');
const toastContainer  = document.getElementById('toast-container');
const addUserBtn      = document.getElementById('add-user-btn');

let currentTicket = null; // pour conserver le ticket en cours de consultation
let currentModule = 'dashboard';

// --- Fonctions utilitaires ---

/**
 * Met à jour l'affichage des statistiques globales sur le dashboard.
 */
function updateStats() {
  const total   = tickets.length;
  const open    = tickets.filter(t => t.status === 'Ouvert' || t.status === 'En cours').length;
  const risk    = tickets.filter(t => t.status === 'À risque').length;
  const closed  = tickets.filter(t => t.status === 'Clôturé').length;
  statTotal.textContent  = total;
  statOpen.textContent   = open;
  statRisk.textContent   = risk;
  statClosed.textContent = closed;
}

/**
 * Affiche une notification temporaire.
 * @param {string} message Le message à afficher
 * @param {string} [type] Type de notification: success | warning | error
 */
function showToast(message, type = 'info') {
  const toast = document.createElement('div');
  toast.classList.add('toast');
  // Ajuste la couleur selon le type
  switch (type) {
    case 'success': toast.style.background = getComputedStyle(document.documentElement).getPropertyValue('--color-success'); break;
    case 'warning': toast.style.background = getComputedStyle(document.documentElement).getPropertyValue('--color-warning'); break;
    case 'error': toast.style.background = getComputedStyle(document.documentElement).getPropertyValue('--color-danger'); break;
    default: break;
  }
  toast.textContent = message;
  toastContainer.appendChild(toast);
  setTimeout(() => {
    toast.remove();
  }, 4000);
}

/**
 * Change de module en affichant la section correspondant au nom fourni.
 * @param {string} moduleName Le nom de la section à afficher
 */
function showModule(moduleName) {
  currentModule = moduleName;
  modules.forEach(section => {
    if (section.id === moduleName) {
      section.classList.add('active');
    } else {
      section.classList.remove('active');
    }
  });
  // Mettre à jour l'état actif dans le menu
  const items = sidebar.querySelectorAll('li');
  items.forEach(item => {
    if (item.dataset.module === moduleName) {
      item.classList.add('active');
    } else if (item.id !== 'logout') {
      item.classList.remove('active');
    }
  });
  // Pour certains modules, on recharge le contenu
  if (moduleName === 'dashboard') {
    renderTicketTable();
    updateStats();
  } else if (moduleName === 'users') {
    renderUserTable();
  } else if (moduleName === 'reporting') {
    renderCharts();
  }
}

/**
 * Rendu du tableau des tickets avec filtres actifs.
 * Si aucune liste n'est fournie, filtre à partir de tickets globaux.
 * @param {Array} [list] Liste des tickets à afficher
 */
function renderTicketTable(list) {
  const rows = (list || getFilteredTickets()).map(ticket => {
    return `<tr>
      <td>${ticket.id}</td>
      <td>${ticket.title}</td>
      <td>${ticket.priority}</td>
      <td>${ticket.company || '-'}</td>
      <td>${ticket.status}</td>
      <td class="actions-btn">
        <button class="action view" data-action="view" data-id="${ticket.id}">Voir</button>
        <button class="action assign" data-action="assign" data-id="${ticket.id}">Affecter</button>
      </td>
    </tr>`;
  }).join('');
  ticketsBody.innerHTML = rows;
}

/**
 * Retourne la liste filtrée des tickets en fonction des recherches et filtres actifs.
 */
function getFilteredTickets() {
  const query  = searchInput.value.trim().toLowerCase();
  const status = filterStatus.value;
  const company = filterCompany.value;
  return tickets.filter(ticket => {
    const matchesQuery   = ticket.title.toLowerCase().includes(query) || ticket.description.toLowerCase().includes(query);
    const matchesStatus  = !status || ticket.status === status;
    const matchesCompany = !company || ticket.company === company;
    return matchesQuery && matchesStatus && matchesCompany;
  });
}

/**
 * Rendu du tableau des utilisateurs.
 */
function renderUserTable() {
  const rows = users.map(u => {
    return `<tr>
      <td>${u.id}</td>
      <td>${u.name}</td>
      <td>${u.role}</td>
      <td>${u.company}</td>
      <td class="actions-btn">
        <button class="action edit" data-id="${u.id}" data-action="edit-user">Modifier</button>
        <button class="action delete" data-id="${u.id}" data-action="delete-user">Supprimer</button>
      </td>
    </tr>`;
  }).join('');
  usersBody.innerHTML = rows;
}

/**
 * Affiche un formulaire dans la modale pour ajouter ou modifier un utilisateur.
 * @param {Object|null} user Utilisateur existant à modifier ou null pour ajouter
 */
function openUserModal(user = null) {
  modalTitle.textContent = user ? 'Modifier un utilisateur' : 'Ajouter un utilisateur';
  // Construit le formulaire HTML dynamiquement
  modalBody.innerHTML = `
    <form id="user-form">
      <label for="user-name">Nom</label>
      <input type="text" id="user-name" value="${user ? user.name : ''}" required>
      <label for="user-role">Rôle</label>
      <select id="user-role" required>
        <option value="Admin" ${user && user.role === 'Admin' ? 'selected' : ''}>Admin</option>
        <option value="Gestionnaire" ${user && user.role === 'Gestionnaire' ? 'selected' : ''}>Gestionnaire</option>
        <option value="Support" ${user && user.role === 'Support' ? 'selected' : ''}>Support</option>
      </select>
      <label for="user-company">Société</label>
      <input type="text" id="user-company" value="${user ? user.company : ''}" required>
    </form>
  `;
  // Enregistre le callback de sauvegarde
  modalSave.onclick = () => {
    const name     = document.getElementById('user-name').value.trim();
    const role     = document.getElementById('user-role').value;
    const company  = document.getElementById('user-company').value.trim();
    if (!name || !company) return;
    if (user) {
      // Mise à jour
      user.name = name;
      user.role = role;
      user.company = company;
      showToast('Utilisateur modifié avec succès', 'success');
    } else {
      // Ajout
      const newId = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1;
      users.push({ id: newId, name, role, company });
      showToast('Nouvel utilisateur ajouté', 'success');
    }
    renderUserTable();
    closeModal();
  };
  openModal();
}

/**
 * Ouvre la modale et affiche son contenu actuel.
 */
function openModal() {
  modal.classList.remove('hidden');
}

/**
 * Ferme la modale et réinitialise le contenu.
 */
function closeModal() {
  modal.classList.add('hidden');
  modalBody.innerHTML = '';
  modalTitle.textContent = '';
  // Réinitialiser les callbacks pour éviter les fuites de référence
  modalSave.onclick = null;
}

// Gestion de la fermeture via le bouton Annuler
modalCancel.addEventListener('click', closeModal);

/**
 * Ouvre la page de qualification d'un ticket avec ses détails et permet la mise à jour.
 * @param {Object} ticket Le ticket sélectionné
 */
function openTicketQualification(ticket) {
  currentTicket = ticket;
  showModule('qualification');
  const container = document.getElementById('qualification-content');
  container.innerHTML = `
    <h3>Détails du ticket #${ticket.id}</h3>
    <p><strong>Titre :</strong> ${ticket.title}</p>
    <p><strong>Description :</strong> ${ticket.description}</p>
    <p><strong>Date de création :</strong> ${new Date(ticket.created).toLocaleString()}</p>
    <form id="qualification-form">
      <label for="ticket-priority">Priorité</label>
      <select id="ticket-priority">
        <option value="Basse" ${ticket.priority === 'Basse' ? 'selected' : ''}>Basse</option>
        <option value="Moyenne" ${ticket.priority === 'Moyenne' ? 'selected' : ''}>Moyenne</option>
        <option value="Haute" ${ticket.priority === 'Haute' ? 'selected' : ''}>Haute</option>
        <option value="Critique" ${ticket.priority === 'Critique' ? 'selected' : ''}>Critique</option>
      </select>
      <label for="ticket-company">Société assignée</label>
      <select id="ticket-company">
        <option value="">Non assignée</option>
        ${supportCompanies.map(comp => `<option value="${comp}" ${ticket.company === comp ? 'selected' : ''}>${comp}</option>`).join('')}
      </select>
      <label for="ticket-comments">Commentaires de qualification</label>
      <textarea id="ticket-comments" rows="3" placeholder="Ajouter un commentaire..."></textarea>
      <button type="submit">Enregistrer la qualification</button>
    </form>
  `;
  // Listener du formulaire
  const form = document.getElementById('qualification-form');
  form.onsubmit = (e) => {
    e.preventDefault();
    const newPriority = document.getElementById('ticket-priority').value;
    const newCompany  = document.getElementById('ticket-company').value;
    const comments    = document.getElementById('ticket-comments').value.trim();
    ticket.priority = newPriority;
    ticket.company  = newCompany;
    // Lorsque le ticket est qualifié et assigné, on le passe à "En cours"
    if (ticket.status === 'Ouvert' && newCompany) {
      ticket.status = 'En cours';
    }
    showToast('Ticket qualifié et mis à jour', 'success');
    // On revient au dashboard et on rafraîchit
    showModule('dashboard');
  };
}

/**
 * Ouvre le module de supervision/contrôle qualité pour un ticket.
 * Permet d'évaluer et de clôturer une demande.
 * @param {Object} ticket Le ticket sélectionné
 */
function openSupervision(ticket) {
  currentTicket = ticket;
  showModule('supervision');
  const container = document.getElementById('supervision-content');
  container.innerHTML = `
    <h3>Supervision du ticket #${ticket.id}</h3>
    <p><strong>Titre :</strong> ${ticket.title}</p>
    <p><strong>Description :</strong> ${ticket.description}</p>
    <p><strong>Société assignée :</strong> ${ticket.company || 'Non assignée'}</p>
    <p><strong>Statut actuel :</strong> ${ticket.status}</p>
    <form id="supervision-form">
      <label for="ticket-status">Mettre à jour le statut</label>
      <select id="ticket-status">
        <option value="En cours" ${ticket.status === 'En cours' ? 'selected' : ''}>En cours</option>
        <option value="À risque" ${ticket.status === 'À risque' ? 'selected' : ''}>À risque</option>
        <option value="Clôturé" ${ticket.status === 'Clôturé' ? 'selected' : ''}>Clôturé</option>
      </select>
      <label for="quality-comments">Commentaires / validation qualité</label>
      <textarea id="quality-comments" rows="3" placeholder="Notes de validation qualité..."></textarea>
      <button type="submit">Mettre à jour</button>
    </form>
  `;
  const form = document.getElementById('supervision-form');
  form.onsubmit = (e) => {
    e.preventDefault();
    const newStatus = document.getElementById('ticket-status').value;
    ticket.status = newStatus;
    if (newStatus === 'Clôturé') {
      showToast('Ticket clôturé', 'success');
    } else if (newStatus === 'À risque') {
      ticket.risk = true;
      showToast('Ticket marqué comme à risque', 'warning');
    } else {
      showToast('Statut du ticket mis à jour', 'success');
    }
    showModule('dashboard');
  };
}

/**
 * Prépare et affiche les graphiques du module reporting sans utiliser Chart.js.
 * Dessine des graphiques simples sur les canvas.
 */
function renderCharts() {
  // Calcul des données
  const statusCounts = { 'Ouvert': 0, 'En cours': 0, 'À risque': 0, 'Clôturé': 0 };
  tickets.forEach(t => { if (statusCounts[t.status] !== undefined) statusCounts[t.status]++; });
  const companyCounts = {};
  supportCompanies.forEach(comp => { companyCounts[comp] = 0; });
  tickets.forEach(t => { if (t.company) companyCounts[t.company] = (companyCounts[t.company] || 0) + 1; });

  // Canvas pour statut
  const canvasStatus  = document.getElementById('ticketsChart');
  const ctxStatus     = canvasStatus.getContext('2d');
  const statuses      = Object.keys(statusCounts);
  const counts        = Object.values(statusCounts);
  const maxStatus     = Math.max(...counts, 1);
  const barWidth      = (canvasStatus.width / statuses.length) * 0.6;
  const gap           = (canvasStatus.width / statuses.length) * 0.4;
  ctxStatus.clearRect(0, 0, canvasStatus.width, canvasStatus.height);
  // titre
  ctxStatus.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-text');
  ctxStatus.font = '14px Segoe UI';
  ctxStatus.textAlign = 'center';
  ctxStatus.fillText('Tickets par statut', canvasStatus.width / 2, 14);
  ctxStatus.font = '12px Segoe UI';
  statuses.forEach((status, i) => {
    const barHeight = (counts[i] / maxStatus) * (canvasStatus.height - 40);
    const x = i * (barWidth + gap) + gap / 2;
    const y = canvasStatus.height - barHeight - 20;
    ctxStatus.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-primary');
    ctxStatus.fillRect(x, y, barWidth, barHeight);
    ctxStatus.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-text');
    ctxStatus.fillText(status, x + barWidth / 2, canvasStatus.height - 5);
    ctxStatus.fillText(counts[i], x + barWidth / 2, y - 5);
  });

  // Canvas pour société
  const canvasCompany = document.getElementById('companyChart');
  const ctxCompany    = canvasCompany.getContext('2d');
  const comps         = Object.keys(companyCounts);
  const compCounts    = Object.values(companyCounts);
  const maxComp       = Math.max(...compCounts, 1);
  const barHeightComp = (canvasCompany.height / comps.length) * 0.6;
  const gapV          = (canvasCompany.height / comps.length) * 0.4;
  ctxCompany.clearRect(0, 0, canvasCompany.width, canvasCompany.height);
  ctxCompany.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-text');
  ctxCompany.font = '14px Segoe UI';
  ctxCompany.textAlign = 'center';
  ctxCompany.fillText('Tickets par société', canvasCompany.width / 2, 14);
  ctxCompany.font = '12px Segoe UI';
  ctxCompany.textAlign = 'left';
  comps.forEach((comp, i) => {
    const barLen = (compCounts[i] / maxComp) * (canvasCompany.width - 150);
    const y = i * (barHeightComp + gapV) + gapV / 2 + 20;
    ctxCompany.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-secondary');
    ctxCompany.fillRect(120, y, barLen, barHeightComp);
    ctxCompany.fillStyle = getComputedStyle(document.documentElement).getPropertyValue('--color-text');
    ctxCompany.fillText(comp, 10, y + barHeightComp / 2);
    ctxCompany.fillText(compCounts[i], 120 + barLen + 10, y + barHeightComp / 2);
  });
}

/**
 * Initialisation de l'application : configuration des écouteurs d'événements et rendu initial.
 */
function init() {
  // Soumission du formulaire de connexion
  loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    // On ne vérifie pas le mot de passe dans cette maquette
    loginScreen.classList.add('hidden');
    mainContainer.classList.remove('hidden');
    showModule('dashboard');
    showToast('Connexion réussie', 'success');
  });

  // Gestion du clic sur le menu latéral
  sidebar.addEventListener('click', (e) => {
    const target = e.target;
    if (target.tagName === 'LI') {
      if (target.id === 'logout') {
        // Retour à la page de connexion
        mainContainer.classList.add('hidden');
        loginScreen.classList.remove('hidden');
      } else if (target.dataset.module) {
        showModule(target.dataset.module);
      }
    }
  });

  // Écoute des filtres de recherche pour mise à jour en temps réel
  [searchInput, filterStatus, filterCompany].forEach(input => {
    input.addEventListener('input', () => {
      renderTicketTable();
    });
  });

  // Gestion des actions sur le tableau des tickets (délégation d'événements)
  ticketsBody.addEventListener('click', (e) => {
    const btn = e.target;
    const id  = parseInt(btn.getAttribute('data-id'));
    const action = btn.getAttribute('data-action');
    const ticket = tickets.find(t => t.id === id);
    if (!ticket) return;
    if (action === 'view') {
      // Accès à la qualification
      openTicketQualification(ticket);
    } else if (action === 'assign') {
      // Assignation rapide via modale
      openAssignModal(ticket);
    }
  });

  // Gestion des actions sur la table utilisateur
  usersBody.addEventListener('click', (e) => {
    const btn = e.target;
    const id  = parseInt(btn.getAttribute('data-id'));
    const action = btn.getAttribute('data-action');
    const user = users.find(u => u.id === id);
    if (!user) return;
    if (action === 'edit-user') {
      openUserModal(user);
    } else if (action === 'delete-user') {
      if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
        users = users.filter(u => u.id !== id);
        renderUserTable();
        showToast('Utilisateur supprimé', 'success');
      }
    }
  });

  // Bouton pour ajouter un utilisateur
  addUserBtn.addEventListener('click', () => {
    openUserModal(null);
  });

  // Fermer la modale en cliquant en dehors du contenu
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      closeModal();
    }
  });

  // Rendu initial
  updateStats();
  renderTicketTable();
  renderUserTable();
  renderCharts();
}

/**
 * Ouvre une modale rapide pour assigner un ticket à une société.
 * @param {Object} ticket Le ticket à assigner
 */
function openAssignModal(ticket) {
  modalTitle.textContent = `Affectation du ticket #${ticket.id}`;
  modalBody.innerHTML = `
    <p>Sélectionnez la société à laquelle affecter cette demande :</p>
    <select id="assign-company">
      ${supportCompanies.map(comp => `<option value="${comp}" ${ticket.company === comp ? 'selected' : ''}>${comp}</option>`).join('')}
    </select>
  `;
  modalSave.onclick = () => {
    const company = document.getElementById('assign-company').value;
    ticket.company = company;
    // Passage en cours si la demande était ouverte
    if (ticket.status === 'Ouvert') ticket.status = 'En cours';
    showToast(`Ticket #${ticket.id} affecté à ${company}`, 'success');
    renderTicketTable();
    updateStats();
    closeModal();
  };
  openModal();
}

// Lancement de l'application lorsque le DOM est prêt
window.addEventListener('DOMContentLoaded', init);
