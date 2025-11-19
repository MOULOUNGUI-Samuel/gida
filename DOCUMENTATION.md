# 📚 Documentation Détaillée - GIDA

## 📁 Structure des Fichiers et Fonctionnalités

### 🗂️ **Dossier `app/` - Logique Métier**

#### 📂 **`app/Http/Controllers/` - Contrôleurs**

##### `HomeController.php` - Contrôleur Principal
**Rôle** : Gestion des pages principales et de la logique métier

**Fonctionnalités principales** :
- **`dashboard()`** : Dashboard administrateur avec statistiques
- **`dashboardEmployer()`** : Interface employé
- **`saveQualification()`** : Qualification et affectation des demandes
- **`assignDemande()`** : Affectation manuelle des demandes
- **`getDemandeDetails()`** : Détails d'une demande pour les modals
- **`qualityControl()`** : Contrôle qualité des demandes
- **`users()`** : Gestion des utilisateurs

**Utilité** : Point d'entrée principal pour toutes les actions utilisateur

##### `UserController.php` - Gestion des Utilisateurs
**Rôle** : API pour la gestion CRUD des utilisateurs

**Fonctionnalités** :
- **`index()`** : Liste des utilisateurs
- **`store()`** : Création d'utilisateur avec mot de passe
- **`show()`** : Détails d'un utilisateur
- **`update()`** : Modification d'utilisateur
- **`destroy()`** : Suppression d'utilisateur

**Utilité** : Interface d'administration des utilisateurs

##### `DemandesController.php` - Gestion des Demandes
**Rôle** : CRUD des demandes d'assistance

**Fonctionnalités** :
- **`index()`** : Liste des demandes
- **`create()`** : Formulaire de création
- **`store()`** : Sauvegarde d'une nouvelle demande
- **`show()`** : Affichage d'une demande
- **`updateStatus()`** : Mise à jour du statut

**Utilité** : Gestion complète du cycle de vie des demandes

##### `NouvelleDemandeController.php` - Création de Demandes
**Rôle** : Interface de création de demandes pour les employés

**Fonctionnalités** :
- **`create()`** : Formulaire de nouvelle demande
- **`store()`** : Traitement de la création
- **`dashboard()`** : Dashboard employé

**Utilité** : Interface simplifiée pour les employés

#### 📂 **`app/Models/` - Modèles de Données**

##### `User.php` - Modèle Utilisateur
**Rôle** : Représentation des utilisateurs en base

**Champs principaux** :
- `nom` : Nom complet
- `email` : Adresse email
- `username` : Nom d'utilisateur
- `type` : Type d'utilisateur (0=Admin, 1=Employé)
- `code_entreprise` : Société d'appartenance
- `password` : Mot de passe hashé

**Relations** :
- `demandes()` : Demandes créées par l'utilisateur
- `assignedDemandes()` : Demandes assignées à l'utilisateur

**Utilité** : Gestion des utilisateurs et authentification

##### `Demandes.php` - Modèle Demande
**Rôle** : Cœur du système - gestion des demandes d'assistance

**Champs principaux** :
- `titre` : Titre de la demande
- `description` : Description détaillée
- `categorie` : Catégorie de la demande
- `priorite` : Priorité (normale, urgente, critique)
- `statut` : Statut actuel
- `societe` : Société demandeur
- `societe_assignee` : Société support assignée
- `workflow_status` : Statut du workflow GIDA
- `score_qualite` : Note de qualité
- `date_escalade` : Date limite avant escalade

**Relations** :
- `user()` : Utilisateur demandeur
- `assignedUser()` : Utilisateur assigné
- `validator()` : Utilisateur validateur

**Scopes utiles** :
- `needingAssignment()` : Demandes nécessitant une affectation
- `overdue()` : Demandes en retard
- `byCompany()` : Demandes par société
- `critical()` : Demandes critiques

**Utilité** : Gestion complète du workflow des demandes

##### `SupportTicket.php` - Modèle Ticket Support (SUPPRIMÉ)
**Rôle** : Ancien système de tickets (remplacé par les demandes)

**Statut** : ❌ **SUPPRIMÉ** - Remplacé par le système de demandes

#### 📂 **`app/Providers/` - Fournisseurs de Services**

##### `AppServiceProvider.php` - Fournisseur Principal
**Rôle** : Configuration des services de l'application

**Fonctionnalités** :
- Configuration des services
- Enregistrement des bindings
- Configuration globale

**Utilité** : Configuration de l'application Laravel

#### 📂 **`app/Services/` - Services Métier**

##### `SmsService.php` - Service SMS
**Rôle** : Envoi de notifications SMS

**Fonctionnalités** :
- Envoi de SMS de notification
- Gestion des templates SMS
- Intégration avec des fournisseurs SMS

**Utilité** : Notifications automatiques aux utilisateurs

### 🗂️ **Dossier `database/` - Base de Données**

#### 📂 **`database/migrations/` - Migrations**

##### `0001_01_01_000000_create_users_table.php`
**Rôle** : Création de la table utilisateurs

**Champs créés** :
- `id` : Identifiant unique
- `nom` : Nom complet
- `email` : Email unique
- `username` : Nom d'utilisateur
- `password` : Mot de passe hashé
- `type` : Type d'utilisateur
- `code_entreprise` : Société
- `matricule` : Matricule unique
- `timestamps` : Dates de création/modification

**Utilité** : Structure de base pour les utilisateurs

##### `2024_01_01_000001_create_demandes_table.php`
**Rôle** : Création de la table demandes

**Champs créés** :
- `id` : Identifiant unique
- `titre` : Titre de la demande
- `categorie` : Catégorie
- `priorite` : Priorité
- `description` : Description détaillée
- `statut` : Statut actuel
- `user_id` : Référence utilisateur
- `timestamps` : Dates

**Utilité** : Structure de base pour les demandes

##### `2025_08_23_extend_demandes_for_gida.php`
**Rôle** : Extension de la table demandes pour GIDA

**Nouveaux champs** :
- `societe_assignee` : Société support assignée
- `assignee_user_id` : Utilisateur assigné
- `workflow_status` : Statut du workflow
- `score_qualite` : Note de qualité
- `date_escalade` : Date d'escalade
- `validateur_id` : ID du validateur
- `commentaire_validation` : Commentaires
- `mots_cles_detectes` : Mots-clés détectés
- `assignment_automatique` : Affectation automatique
- `temps_traitement_minutes` : Temps de traitement

**Utilité** : Fonctionnalités avancées du workflow GIDA

#### 📂 **`database/seeders/` - Données de Test**

##### `DatabaseSeeder.php` - Seeder Principal
**Rôle** : Orchestration des seeders

**Fonctionnalités** :
- Appel des autres seeders
- Création de données de test
- Configuration initiale

**Utilité** : Peuplement de la base avec des données de test

##### `UserSeeder.php` - Seeder Utilisateurs
**Rôle** : Création d'utilisateurs de test

**Fonctionnalités** :
- Création d'utilisateurs admin
- Création d'utilisateurs employés
- Données de test variées

**Utilité** : Tests et démonstration

##### `AdminSeeder.php` - Seeder Administrateurs
**Rôle** : Création d'administrateurs

**Fonctionnalités** :
- Création de comptes admin
- Configuration des permissions
- Données de test admin

**Utilité** : Accès administrateur pour les tests

### 🗂️ **Dossier `resources/views/` - Interface Utilisateur**

#### 📂 **`resources/views/layouts/` - Layouts**

##### `appAdministration.blade.php` - Layout Admin
**Rôle** : Template principal pour l'administration

**Fonctionnalités** :
- Navigation admin
- Menu latéral
- Structure responsive
- Intégration des assets

**Composants** :
- Menu de navigation
- Zone de contenu principale
- Footer
- Scripts et styles

**Utilité** : Interface unifiée pour l'administration

##### `appEmployer.blade.php` - Layout Employé
**Rôle** : Template principal pour les employés

**Fonctionnalités** :
- Navigation employé
- Interface simplifiée
- Accès aux fonctionnalités employé

**Utilité** : Interface unifiée pour les employés

#### 📂 **`resources/views/admin/` - Vues Administration**

##### `dashboard.blade.php` - Dashboard Admin
**Rôle** : Interface principale d'administration

**Fonctionnalités** :
- **Statistiques** : Vue d'ensemble des demandes
- **Tableau des demandes** : Liste avec filtres
- **Actions rapides** : Qualification, affectation
- **Modals** : Détails et actions
- **Filtres** : Par statut, société, priorité

**Composants** :
- Cartes de statistiques
- Tableau interactif
- Modals de qualification
- Système de filtrage
- JavaScript pour l'interactivité

**Utilité** : Centre de contrôle principal pour les administrateurs

##### `users.blade.php` - Gestion Utilisateurs
**Rôle** : Interface de gestion des utilisateurs

**Fonctionnalités** :
- **Liste des utilisateurs** : Tableau avec actions
- **Ajout d'utilisateur** : Modal de création
- **Modification** : Édition en ligne
- **Suppression** : Confirmation
- **Gestion des rôles** : Attribution des types

**Composants** :
- Tableau des utilisateurs
- Modal d'ajout/modification
- Système de badges pour les rôles
- Validation côté client
- API calls pour les actions

**Utilité** : Administration complète des utilisateurs

##### `qualification.blade.php` - Qualification
**Rôle** : Interface de qualification des demandes

**Fonctionnalités** :
- **Liste des demandes** à qualifier
- **Actions de qualification** : Priorité, société
- **Filtres** : Par catégorie, statut
- **Détails** : Modal avec informations

**Utilité** : Qualification manuelle des demandes

##### `supervision.blade.php` - Supervision
**Rôle** : Interface de supervision et contrôle qualité

**Fonctionnalités** :
- **Suivi des demandes** : Statut en temps réel
- **Contrôle qualité** : Validation des résolutions
- **Métriques** : Performance et délais
- **Actions** : Escalade, réassignation

**Utilité** : Supervision du traitement des demandes

##### `reporting.blade.php` - Reporting
**Rôle** : Interface de reporting et KPI

**Fonctionnalités** :
- **Graphiques** : Statistiques visuelles
- **KPI** : Indicateurs de performance
- **Exports** : Rapports PDF/Excel
- **Filtres temporels** : Périodes personnalisées

**Utilité** : Analyse et reporting des performances

#### 📂 **`resources/views/components/` - Composants**

##### `employer/Nouvelledemande.blade.php` - Nouvelle Demande
**Rôle** : Formulaire de création de demande

**Fonctionnalités** :
- **Formulaire complet** : Tous les champs nécessaires
- **Validation** : Côté client et serveur
- **Upload de fichiers** : Pièces jointes
- **Pré-remplissage** : Données utilisateur

**Champs** :
- Titre de la demande
- Catégorie
- Priorité
- Description détaillée
- Date limite
- Informations utilisateur
- Pièces jointes

**Utilité** : Interface de création de demandes pour les employés

##### `employer/Dashboard.blade.php` - Dashboard Employé
**Rôle** : Interface principale pour les employés

**Fonctionnalités** :
- **Vue d'ensemble** : Demandes personnelles
- **Statistiques** : Progression personnelle
- **Actions rapides** : Créer une demande
- **Notifications** : Alertes importantes

**Utilité** : Centre de contrôle pour les employés

#### 📂 **`resources/views/auth/` - Authentification**

##### `login.blade.php` - Connexion
**Rôle** : Page de connexion

**Fonctionnalités** :
- **Formulaire de connexion** : Email/mot de passe
- **Validation** : Erreurs et messages
- **Remember me** : Connexion persistante
- **Responsive** : Design adaptatif

**Utilité** : Point d'entrée sécurisé de l'application

##### `register.blade.php` - Inscription
**Rôle** : Page d'inscription

**Fonctionnalités** :
- **Formulaire d'inscription** : Données utilisateur
- **Validation** : Règles de validation
- **Confirmation** : Vérification des données
- **Redirection** : Après inscription

**Utilité** : Création de nouveaux comptes

### 🗂️ **Dossier `routes/` - Définition des Routes**

#### `web.php` - Routes Web
**Rôle** : Définition de toutes les routes de l'application

**Routes principales** :
- **`/`** : Redirection basée sur le type d'utilisateur
- **`/dashboard`** : Dashboard administrateur
- **`/dashboardEmployer`** : Dashboard employé
- **`/admin/save-qualification`** : Qualification des demandes
- **`/admin/assign-demande`** : Affectation des demandes
- **`/users`** : Gestion des utilisateurs
- **`/Nouvelle-demande`** : Création de demande

**Groupes de routes** :
- **Routes protégées** : Middleware `auth` et `verified`
- **Routes admin** : Fonctionnalités d'administration
- **Routes employé** : Fonctionnalités employé
- **API routes** : Endpoints pour AJAX

**Utilité** : Configuration du routage de l'application

#### `auth.php` - Routes d'Authentification
**Rôle** : Routes pour l'authentification Laravel Breeze

**Routes** :
- **`/login`** : Connexion
- **`/register`** : Inscription
- **`/logout`** : Déconnexion
- **`/forgot-password`** : Mot de passe oublié
- **`/reset-password`** : Réinitialisation

**Utilité** : Gestion de l'authentification

### 🗂️ **Dossier `public/` - Assets Publics**

#### `public/assets/app.js` - JavaScript Principal
**Rôle** : Logique JavaScript côté client

**Fonctionnalités** :
- **Gestion des modals** : Ouverture/fermeture
- **Validation** : Validation côté client
- **API calls** : Communication avec le serveur
- **Filtrage** : Filtres dynamiques
- **Notifications** : Messages utilisateur

**Modules** :
- Gestion des utilisateurs
- Gestion des demandes
- Interface utilisateur
- Utilitaires

**Utilité** : Interactivité et dynamisme de l'interface

#### `public/assets/style.css` - Styles CSS
**Rôle** : Styles personnalisés

**Fonctionnalités** :
- **Design system** : Couleurs, typographie
- **Composants** : Boutons, formulaires, tableaux
- **Responsive** : Design adaptatif
- **Animations** : Transitions et effets

**Utilité** : Personnalisation visuelle de l'interface

### 🗂️ **Dossier `config/` - Configuration**

#### `config/app.php` - Configuration Application
**Rôle** : Configuration générale de Laravel

**Paramètres** :
- Nom de l'application
- Timezone
- Locale
- Debug mode
- Providers

**Utilité** : Configuration de base de l'application

#### `config/database.php` - Configuration Base de Données
**Rôle** : Configuration des connexions de base de données

**Paramètres** :
- Connexion SQLite
- Configuration des migrations
- Paramètres de performance

**Utilité** : Gestion de la base de données

### 🗂️ **Dossier `storage/` - Stockage**

#### `storage/logs/laravel.log` - Logs Application
**Rôle** : Journal des événements de l'application

**Contenu** :
- Erreurs et exceptions
- Actions utilisateur
- Performance
- Debug information

**Utilité** : Monitoring et débogage

## 🔧 **Fonctionnalités Détaillées**

### 🎯 **Système d'Authentification**
- **Laravel Breeze** : Authentification simple et sécurisée
- **Types d'utilisateurs** : Admin (0) et Employé (1)
- **Redirection automatique** : Basée sur le type d'utilisateur
- **Sessions sécurisées** : Protection CSRF

### 📊 **Dashboard Administrateur**
- **Statistiques en temps réel** : Demandes, statuts, performances
- **Tableau interactif** : Liste des demandes avec actions
- **Filtres avancés** : Par statut, société, priorité, date
- **Actions rapides** : Qualification, affectation, contrôle qualité
- **Modals dynamiques** : Détails et actions sans rechargement

### 🏢 **Gestion des Sociétés Support**
- **4 sociétés spécialisées** : COMKETING, YOD INGÉNIERIE, FCI, ALPHON CONSULTING
- **Affectation automatique** : Basée sur les mots-clés
- **Affectation manuelle** : Par l'administrateur
- **Suivi des performances** : Temps de traitement, qualité

### 🔄 **Workflow des Demandes**
- **7 étapes** : Nouvelle → Analysée → Assignée → En traitement → Résolue → Validée → Clôturée
- **Escalade automatique** : Basée sur les priorités et délais
- **Notifications** : Alertes en temps réel
- **Historique complet** : Traçabilité des actions

### 👥 **Gestion des Utilisateurs**
- **CRUD complet** : Création, lecture, modification, suppression
- **Gestion des rôles** : Attribution des types d'utilisateur
- **Mots de passe sécurisés** : Hachage et validation
- **Interface intuitive** : Modals et actions rapides

### 📱 **Interface Responsive**
- **Design adaptatif** : Mobile, tablette, desktop
- **Composants modernes** : Cards, modals, tableaux
- **Animations fluides** : Transitions et effets
- **Accessibilité** : Standards WCAG

### 🔒 **Sécurité**
- **Protection CSRF** : Tokens sur toutes les actions
- **Validation** : Côté client et serveur
- **Authentification** : Sessions sécurisées
- **Autorisations** : Basées sur les types d'utilisateur

## 🚀 **Déploiement et Maintenance**

### 📦 **Déploiement**
1. **Configuration serveur** : PHP 8.1+, MySQL/PostgreSQL
2. **Variables d'environnement** : Configuration production
3. **Optimisation** : Cache, compression, CDN
4. **Monitoring** : Logs, performance, erreurs

### 🔄 **Maintenance**
- **Sauvegardes** : Base de données et fichiers
- **Mises à jour** : Laravel et dépendances
- **Monitoring** : Performance et disponibilité
- **Support** : Documentation et assistance

---

**Cette documentation détaillée couvre l'ensemble du projet GIDA, expliquant chaque fichier, fonctionnalité et leur utilité dans le système global.** 📚
