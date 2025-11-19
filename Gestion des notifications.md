# 🚨 Gestion des notifications

## 1. 🛠️ Création et intégration
- Une table **`notifications`** a été créée avec son **modèle** et son **contrôleur**.  
- Des liens ont été ajoutés dans les **layouts** des employés et des administrateurs pour accéder aux notifications depuis le menu.  
- Dans le fichier **`AppServiceProvider`**, une requête récupère le **nombre total de notifications non lues** pour la personne connectée.  
  → Ce compteur s’affiche directement dans le **menu**.

---

## 2. 🎛️ Contrôleurs
- Le contrôleur qui gère l’affichage des notifications est **`HomeController`**.  
- Fonctions utilisées :  
  - `notification` → pour les **employés**  
  - `notificationAdmin` → pour les **administrateurs**  
- Vues correspondantes :  
  - `resources/views/admin/notification.blade.php` (**administrateurs**)  
  - `resources/views/components/employer/notification.blade.php` (**employés**)  

---

## 3. 🎨 Affichage des notifications
- 🔵 **Nouvelles notifications** → affichées en **bleu**  
- 🟡 **Notifications déjà lues** → affichées en **jaune**  

---

## 4. ➕ Ajout d’une notification
Les notifications sont générées automatiquement via **`app/observers/DemandeObserver`** :  
- Lors de **l’ajout** d’une demande.  
- Lors de la **modification (update)** d’une demande.  

👤 Les informations de la personne qui effectue l’action sont enregistrées pour identifier l’émetteur et envoyer la notification aux bons destinataires.  

### Exemple
- Lorsqu’un **employé** crée une demande → **tous les administrateurs** reçoivent une notification.  
- Lorsqu’un **administrateur** change le statut d’une demande → **l’employé concerné** reçoit une notification.  

📧 En parallèle, un **mail est envoyé automatiquement** aux destinataires.  

---

## 5. 📩 Gestion des mails
- Les mails sont envoyés via le protocole **SMTP**.  
- Les informations de configuration se trouvent dans le fichier **`.env`** :  
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=ton.email@gmail.com
  MAIL_PASSWORD=mot_de_passe_application   ( evitez les espaces )
  MAIL_ENCRYPTION=tls
