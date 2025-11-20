@extends('layouts.appAdministration')

@section('content')
<div class="container-fluid p-4">
    <div class="mb-4">
        <h2 class="mb-0">Gérer les Utilisateurs - {{ $entreprise->nom }}</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('entreprises.index') }}">Entreprises</a></li>
                <li class="breadcrumb-item"><a href="{{ route('entreprises.show', $entreprise->id) }}">{{ $entreprise->nom }}</a></li>
                <li class="breadcrumb-item active">Gérer les utilisateurs</li>
            </ol>
        </nav>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Utilisateurs disponibles -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Utilisateurs Disponibles</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('entreprises.attach-users', $entreprise->id) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <input type="text" id="searchAvailable" class="form-control"
                                   placeholder="Rechercher un utilisateur...">
                        </div>

                        @if($availableUsers->where('entreprise_id', null)->count() > 0)
                            <div class="user-list" id="availableUsersList" style="max-height: 500px; overflow-y: auto;">
                                @foreach($availableUsers->where('entreprise_id', null) as $user)
                                    <div class="form-check user-item mb-2 p-2 border rounded">
                                        <input class="form-check-input" type="checkbox"
                                               name="user_ids[]" value="{{ $user->id }}"
                                               id="user_{{ $user->id }}">
                                        <label class="form-check-label w-100" for="user_{{ $user->id }}">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $user->nom }}</strong><br>
                                                    <small class="text-muted">
                                                        {{ $user->username }} | {{ $user->matricule }}
                                                    </small>
                                                </div>
                                                @if($user->type == 0)
                                                    <span class="badge bg-danger">Admin</span>
                                                @elseif($user->type == 2)
                                                    <span class="badge bg-info">Support</span>
                                                @else
                                                    <span class="badge bg-primary">Employé</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-3">
                                <button type="button" id="selectAll" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-check-square"></i> Tout sélectionner
                                </button>
                                <button type="button" id="deselectAll" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-square"></i> Tout désélectionner
                                </button>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-plus-circle"></i> Ajouter les utilisateurs sélectionnés
                                </button>
                            </div>
                        @else
                            <p class="text-center text-muted py-4">
                                Aucun utilisateur disponible. Tous les utilisateurs sont déjà assignés à une entreprise.
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Utilisateurs de l'entreprise -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-building"></i> Utilisateurs de l'Entreprise</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <input type="text" id="searchEntreprise" class="form-control"
                               placeholder="Rechercher un utilisateur...">
                    </div>

                    @if($entrepriseUsers->count() > 0)
                        <div class="user-list" id="entrepriseUsersList" style="max-height: 500px; overflow-y: auto;">
                            @foreach($entrepriseUsers as $user)
                                <div class="user-item mb-2 p-2 border rounded bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $user->nom }}</strong><br>
                                            <small class="text-muted">
                                                {{ $user->username }} | {{ $user->matricule }}
                                            </small>
                                        </div>
                                        <div>
                                            @if($user->type == 0)
                                                <span class="badge bg-danger me-2">Admin</span>
                                            @elseif($user->type == 2)
                                                <span class="badge bg-info me-2">Support</span>
                                            @else
                                                <span class="badge bg-primary me-2">Employé</span>
                                            @endif
                                            <form action="{{ route('entreprises.detach-user', [$entreprise->id, $user->id]) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Retirer cet utilisateur de l\'entreprise ?');"
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-user-minus"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center text-muted py-4">
                            Aucun utilisateur dans cette entreprise.
                        </p>
                    @endif

                    <div class="mt-3 text-center">
                        <div class="alert alert-info">
                            <strong>{{ $entrepriseUsers->count() }}</strong> utilisateur(s) dans l'entreprise
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('entreprises.show', $entreprise->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux détails
        </a>
    </div>
</div>

<style>
    .container-fluid {
        background: #f5f7fa;
        min-height: 100vh;
    }

    .card {
        border: none;
        border-radius: 10px;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-top: 10px;
    }

    .user-item {
        transition: all 0.3s ease;
    }

    .user-item:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .form-check-input {
        cursor: pointer;
        width: 20px;
        height: 20px;
    }

    .form-check-label {
        cursor: pointer;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Recherche dans les utilisateurs disponibles
    const searchAvailable = document.getElementById('searchAvailable');
    const availableUsersList = document.getElementById('availableUsersList');

    if (searchAvailable && availableUsersList) {
        searchAvailable.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = availableUsersList.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Recherche dans les utilisateurs de l'entreprise
    const searchEntreprise = document.getElementById('searchEntreprise');
    const entrepriseUsersList = document.getElementById('entrepriseUsersList');

    if (searchEntreprise && entrepriseUsersList) {
        searchEntreprise.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = entrepriseUsersList.querySelectorAll('.user-item');

            userItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    }

    // Sélectionner tous les utilisateurs
    const selectAll = document.getElementById('selectAll');
    if (selectAll) {
        selectAll.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.closest('.user-item').style.display !== 'none') {
                    checkbox.checked = true;
                }
            });
        });
    }

    // Désélectionner tous les utilisateurs
    const deselectAll = document.getElementById('deselectAll');
    if (deselectAll) {
        deselectAll.addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="user_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        });
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
