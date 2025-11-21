<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DemandesController;
use App\Http\Controllers\NouvelleDemandeController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Route d'accueil: afficher le formulaire de connexion si non authentifié.
Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        if ($user->type == 0) {  // admin
            return redirect()->route('dashboard');
        } elseif ($user->type == 2) {  // support entreprise
            return redirect()->route('supportEntreprise.home');
        } else {  // type 1 = employé
            return redirect()->route('dashboardEmployer');
        }
    }

    // Si non authentifié, afficher directement la vue de connexion pour que
    // la première page affichée au lancement du serveur soit le formulaire.
    return view('auth.login');
});

// Routes d'authentification
require __DIR__ . '/auth.php';

// Routes protégées par authentification
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Routes pour l'administration
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/qualification', [HomeController::class, 'qualification'])->name('qualification');
    Route::get('/supervision', [HomeController::class, 'supervision'])->name('supervision');
    Route::get('/reporting', [HomeController::class, 'reporting'])->name('reporting');
    Route::get('/users', [HomeController::class, 'users'])->name('users');
    Route::get('/notificationsAdmin', [HomeController::class, 'notificationAdmin'])->name('notificationAdmin');

    // Routes pour la gestion des utilisateurs
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    
    // Routes pour la qualification et les détails des demandes
    Route::post('/admin/save-qualification', [HomeController::class, 'saveQualification'])->name('admin.save-qualification');
    Route::get('/admin/demande/{id}/details', [HomeController::class, 'getDemandeDetails'])->name('admin.demande-details');
    Route::post('/admin/assign-demande', [DemandesController::class, 'assignDemande'])->name('admin.assign-demande');
    Route::get('/admin/quality-control/{id}', [HomeController::class, 'qualityControl'])->name('admin.quality-control');
    Route::post('/admin/quality-control', [HomeController::class, 'saveQualityControl'])->name('admin.save-quality-control');
    
    // Routes pour les employés
    Route::get('/dashboardEmployer', [HomeController::class, 'dashboardEmployer'])->name('dashboardEmployer');
    Route::get('/Nouvelle-demande', [HomeController::class, 'nouvelledemande'])->name('nouvelledemande');
    Route::get('/historique', [HomeController::class, 'historique'])->name('historique');
    Route::get('/messagerie', [HomeController::class, 'messagerie'])->name('messagerie');
    Route::get('/evaluation', [HomeController::class, 'evaluation'])->name('evaluation');
    Route::get('/notifications', [HomeController::class, 'notification'])->name('notifications');
    Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
    Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    
    // Routes pour le dashboard Support Entreprise
    Route::prefix('support-entreprise')
        ->name('supportEntreprise.')
        ->middleware(['support.entreprise'])
        ->group(function () {
            Route::get('/', [\App\Http\Controllers\SupportEntrepriseController::class, 'dashboard'])->name('dashboard');
            Route::get('/home', [\App\Http\Controllers\SupportEntrepriseController::class, 'home'])->name('home');
            Route::get('/historique', [\App\Http\Controllers\SupportEntrepriseController::class, 'historique'])->name('historique');
            Route::get('/profil', [\App\Http\Controllers\SupportEntrepriseController::class, 'profil'])->name('profil');
            Route::get('/profil/edit', [\App\Http\Controllers\SupportEntrepriseController::class, 'editProfil'])->name('profil.edit');
            Route::post('/profil/update', [\App\Http\Controllers\SupportEntrepriseController::class, 'updateProfil'])->name('profil.update');
            Route::get('/notifications', [\App\Http\Controllers\SupportEntrepriseController::class, 'notifications'])->name('notifications');
        });
    
    // Routes pour les demandes (CRUD)
    Route::resource('demandes', DemandesController::class);
    Route::patch('/demandes/{demande}/status', [DemandesController::class, 'updateStatus'])->name('demandes.updateStatus');

    // Routes pour les évaluations
    Route::resource('evaluations', \App\Http\Controllers\EvaluationController::class)->only(['store']);

    //Routes Notifications
    Route::get('/notification/{id}', [NotificationController::class, 'notification_show'])->name('notifications.notification_show');
    Route::get('/notificationAdmin/{id}', [NotificationController::class, 'notificationAdmin_show'])->name('notifications.notificationAdmin_show');

    // Routes pour les entreprises
    Route::resource('entreprises', EntrepriseController::class);
    Route::get('/entreprises/{entreprise}/add-users', [EntrepriseController::class, 'addUsers'])->name('entreprises.add-users');
    Route::post('/entreprises/{entreprise}/attach-users', [EntrepriseController::class, 'attachUsers'])->name('entreprises.attach-users');
    Route::delete('/entreprises/{entreprise}/users/{user}', [EntrepriseController::class, 'detachUser'])->name('entreprises.detach-user');
    Route::patch('/entreprises/{entreprise}/toggle-active', [EntrepriseController::class, 'toggleActive'])->name('entreprises.toggle-active');

    
    // API Routes pour la gestion des utilisateurs et entreprises
    Route::prefix('api')->group(function () {
        // Users API
        Route::get('/users', [UserController::class, 'index']);
        Route::post('/users', [UserController::class, 'store']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);

        // Entreprises API
        Route::get('/entreprises', [EntrepriseController::class, 'apiIndex']);

        // API Routes pour le chatbot (commentées car ChatbotController n'existe pas encore)
        // Route::post('/chatbot/message', [ChatbotController::class, 'sendMessage']);
        // Route::get('/chatbot/history', [ChatbotController::class, 'getChatHistory']);
        // Route::post('/chatbot/escalate', [ChatbotController::class, 'escalateToAdmin']);

    });
});



    Route::get('/gida/authenticate/{id}', [AuthenticatedSessionController::class, 'authenticate'])
         ->name('gida.authenticate');
     
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.post');

Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
