# 🔑 Identifiants de Connexion - GIDA

## 📋 Format de Connexion

Pour se connecter, l'utilisateur doit fournir :
- **Code Entreprise** : Le code de l'entreprise (ex: FCI, YOD, COMKET, etc.)
- **Username** : Le nom d'utilisateur unique
- **Mot de passe** : Le mot de passe de l'utilisateur

---

## 👤 Comptes Disponibles

### 🔴 Administrateur Système
**Pas d'entreprise - Accès total au système**

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| SYSTEM | admin | password123 | Admin | Administrateur Système |

---

### 🏢 Entreprise FCI (Finance/Investissement)

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| FCI | jean.dupont | password123 | Gestionnaire | Gestionnaire Financier |
| FCI | marie.martin | password123 | Employé | Analyste Financier |
| FCI | support.fci | password123 | Support | Support Client |

---

### 🏢 Entreprise YOD (YOD INGÉNIERIE)

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| YOD | pierre.bernard | password123 | Gestionnaire | Chef de Projet |
| YOD | sophie.leroy | password123 | Employé | Ingénieur Études |

---

### 🏢 Entreprise COMKET (Marketing/Communication)

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| COMKET | luc.dubois | password123 | Gestionnaire | Directeur Marketing |
| COMKET | emma.rousseau | password123 | Employé | Chargée de Communication |

---

### 🏢 Entreprise ALPHON (ALPHON CONSULTING)

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| ALPHON | thomas.petit | password123 | Gestionnaire | Consultant Senior |
| ALPHON | laura.moreau | password123 | Employé | Consultante |

---

### 🔧 Compte Test (Sans entreprise)

| Code Entreprise | Username | Mot de passe | Type | Fonction |
|----------------|----------|--------------|------|----------|
| TEST | test.user | password123 | Employé | Testeur |

---

## 📝 Notes Importantes

1. **Tous les mots de passe sont identiques** : `password123` (à changer en production !)

2. **Types d'utilisateurs** :
   - **Type 0** : Administrateur (accès complet au système)
   - **Type 1** : Employé (accès standard entreprise)
   - **Type 2** : Entreprise Support (support client entreprise)

3. **Exemple de connexion** :
   ```
   Code Entreprise : FCI
   Username        : jean.dupont
   Mot de passe    : password123
   ```

4. **Pour l'administrateur système** :
   ```
   Code Entreprise : SYSTEM
   Username        : admin
   Mot de passe    : password123
   ```

---

## 🚀 Commandes Utiles

### Réinitialiser et peupler la base de données
```bash
php artisan migrate:fresh --seed
```

### Exécuter uniquement les seeders
```bash
php artisan db:seed --class=EntrepriseSeeder
php artisan db:seed --class=UserSeeder
```

### Vérifier les données
```bash
php artisan tinker
>>> \App\Models\Entreprise::all();
>>> \App\Models\User::with('entreprise')->get();
```

---

## 🔐 Sécurité

⚠️ **IMPORTANT** : Ces identifiants sont pour le développement et les tests uniquement.
En production :
- Changez tous les mots de passe
- Utilisez des mots de passe forts et uniques
- Activez l'authentification à deux facteurs si possible
- Ne commitez JAMAIS ce fichier dans un dépôt public

---

**Date de création** : 2025-11-19
**Version** : 1.0
