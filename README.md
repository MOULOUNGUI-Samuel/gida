# 🏢 GIDA - Gestion Intégrée des Demandes d'Assistance

## 📋 Description du Projet

**GIDA** est une plateforme web de gestion des demandes d'assistance développée avec **Laravel 10** et **PHP 8.3**. Elle permet aux entreprises de gérer efficacement les demandes d'assistance de leurs employés en les affectant automatiquement aux sociétés support appropriées.

## 🎯 Objectifs du Projet

- **Centralisation** des demandes d'assistance
- **Affectation automatique** des demandes aux sociétés support
- **Suivi du workflow** de traitement des demandes
- **Interface d'administration** pour la supervision
- **Gestion des utilisateurs** et des rôles

## 🏗️ Architecture

### Technologies Utilisées
- **Backend** : Laravel 10 (PHP 8.3)
- **Frontend** : HTML5, CSS3, JavaScript (Vanilla)
- **Base de données** : SQLite (développement)
- **Serveur** : Laravel Artisan Serve
- **Authentification** : Laravel Breeze

### Structure du Projet
```
FCI2/
├── app/
│   ├── Http/Controllers/     # Contrôleurs
│   ├── Models/              # Modèles Eloquent
│   ├── Providers/           # Fournisseurs de services
│   └── Services/            # Services métier
├── database/
│   ├── migrations/          # Migrations de base de données
│   └── seeders/            # Seeders pour les données de test
├── resources/
│   └── views/              # Vues Blade
├── routes/                 # Définition des routes
└── public/                 # Assets publics
```

## 🚀 Installation et Configuration

### Prérequis
- PHP 8.1 ou supérieur
- Composer
- Node.js (optionnel pour les assets)

### Installation

1. **Cloner le projet**
   ```bash
   git clone [URL_DU_REPO]
   cd FCI2
   ```

2. **Installer les dépendances**
   ```bash
   composer install
   npm install
   ```

3. **Configuration de l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configuration de la base de données**
   ```bash
   # SQLite (recommandé pour le développement)
   touch database/database.sqlite
   ```

5. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

6. **Seeder les données de test**
   ```bash
   php artisan db:seed
   ```

7. **Démarrer le serveur**
   ```bash
   php artisan serve
   ```

### Accès à l'application
- **URL** : http://localhost:8000
- **Compte Admin** : admin@gida.com / password
- **Compte Employé** : employe@gida.com / password

## 👥 Types d'Utilisateurs

### 1. **Administrateurs (Type 0)**
- **Accès** : Dashboard complet
- **Fonctionnalités** :
  - Gestion des demandes
  - Qualification et affectation
  - Contrôle qualité
  - Gestion des utilisateurs
  - Reporting et KPI

### 2. **Employés (Type 1)**
- **Accès** : Interface employé
- **Fonctionnalités** :
  - Création de demandes
  - Suivi de leurs demandes
  - Messagerie
  - Profil utilisateur

## 🏢 Sociétés Support

Le système gère 4 sociétés support spécialisées :

1. **COMKETING** - Marketing/Communication
2. **YOD INGÉNIERIE** - Technique/Ingénierie
3. **FCI** - Finance/Investissement
4. **ALPHON CONSULTING** - Management/Organisation

## 🔄 Workflow des Demandes

### Étapes du Processus
1. **Nouvelle** → Demande créée par l'employé
2. **Analysée** → Qualification par l'admin
3. **Assignée** → Affectation à une société support
4. **En traitement** → Traitement par la société
5. **Résolue** → Demande traitée
6. **Validée** → Validation par l'admin
7. **Clôturée** → Demande terminée

## 📊 Fonctionnalités Principales

### Dashboard Administrateur
- **Vue d'ensemble** des demandes
- **Statistiques** en temps réel
- **Filtres** avancés
- **Actions rapides** (qualification, affectation)

### Gestion des Demandes
- **Création** de nouvelles demandes
- **Qualification** automatique et manuelle
- **Affectation** aux sociétés support
- **Suivi** du statut

### Interface Employé
- **Formulaire** de création de demandes
- **Historique** personnel
- **Notifications** en temps réel
- **Profil** utilisateur

## 🔧 Configuration Avancée

### Variables d'Environnement
```env
APP_NAME=GIDA
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### Personnalisation
- **Sociétés support** : Modifier dans `database/migrations/`
- **Workflow** : Configurer dans `app/Models/Demandes.php`
- **Interface** : Personnaliser dans `resources/views/`

## 📈 Monitoring et Maintenance

### Logs
- **Application** : `storage/logs/laravel.log`
- **Erreurs** : Surveiller les logs Laravel
- **Performance** : Utiliser Laravel Telescope (optionnel)

### Sauvegarde
```bash
# Sauvegarde de la base de données
php artisan db:backup

# Export des données
php artisan db:export
```

## 🛠️ Développement

### Commandes Utiles
```bash
# Créer un nouveau contrôleur
php artisan make:controller NomController

# Créer une nouvelle migration
php artisan make:migration nom_migration

# Vider le cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Tester l'application
php artisan test
```

### Structure des Tests
- **Tests Unitaires** : `tests/Unit/`
- **Tests d'Intégration** : `tests/Feature/`

## 🤝 Contribution

### Guidelines
1. **Fork** le projet
2. **Créer** une branche feature
3. **Commiter** les changements
4. **Pousser** vers la branche
5. **Créer** une Pull Request

### Standards de Code
- **PSR-12** pour PHP
- **ESLint** pour JavaScript
- **Commentaires** en français
- **Documentation** à jour

## 📞 Support

### Contact
- **Email** : support@gida.com
- **Documentation** : `/docs`
- **Issues** : GitHub Issues

### Ressources
- **Documentation Laravel** : https://laravel.com/docs
- **Guide de contribution** : `/CONTRIBUTING.md`
- **Changelog** : `/CHANGELOG.md`

## 📄 Licence

Ce projet est sous licence **MIT**. Voir le fichier `LICENSE` pour plus de détails.

---

**GIDA** - Simplifiez la gestion de vos demandes d'assistance ! 🚀