@extends('layouts.appAdministration')

@section('title', 'Dashboard Administrateur - GIDA')

@section('content')
    <div class="container my-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4 mb-0">Dashboard Administrateur</h1>
        </div>

        {{-- Statistiques --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">Total demandes</h6>
                        <div class="h3 mb-1">{{ $stats['total'] }}</div>
                        <small class="text-success">+12% ce mois</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">En cours</h6>
                        <div class="h3 mb-1">{{ $stats['en_cours'] }}</div>
                        <small class="text-success">+5% cette semaine</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">À risque</h6>
                        <div class="h3 mb-1">{{ $stats['a_risque'] }}</div>
                        <small class="text-danger">+2 aujourd'hui</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-body py-3">
                        <h6 class="text-muted mb-1">Clôturées</h6>
                        <div class="h3 mb-1">{{ $stats['cloturees'] }}</div>
                        <small class="text-success">+8% ce mois</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtres (simple GET, sans JS) --}}
        <form method="GET" class="row g-2 align-items-end mb-3">
            <div class="col-md-4">
                <label for="search-input" class="form-label">Recherche</label>
                <input type="text" id="search-input" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="Rechercher une demande...">
            </div>

            <div class="col-md-2">
                <label for="filter-status" class="form-label">Statut</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="en attente" {{ request('status') === 'en attente' ? 'selected' : '' }}>En attente
                    </option>
                    <option value="en cours" {{ request('status') === 'en cours' ? 'selected' : '' }}>En cours</option>
                    <option value="à risque" {{ request('status') === 'à risque' ? 'selected' : '' }}>À risque</option>
                    <option value="clôturé" {{ request('status') === 'clôturé' ? 'selected' : '' }}>Clôturé</option>
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-company" class="form-label">Société demandeur</label>
                <select id="filter-company" name="company" class="form-select">
                    <option value="">Toutes</option>
                    @foreach ($societes as $id => $nom)
                        <option value="{{ $nom }}" {{ request('company') === $nom ? 'selected' : '' }}>
                            {{ $nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="filter-assigned-company" class="form-label">Société affectée</label>
                <select id="filter-assigned-company" name="assigned_company" class="form-select">
                    <option value="">Toutes</option>
                    @foreach ($societes as $id => $nom)
                        <option value="{{ $nom }}" {{ request('assigned_company') === $nom ? 'selected' : '' }}>
                            {{ $nom }}
                        </option>
                    @endforeach
                    <option value="non-assignee" {{ request('assigned_company') === 'non-assignee' ? 'selected' : '' }}>
                        Non affectées
                    </option>
                </select>
            </div>

            <div class="col-12 text-end mt-2">
                <button type="submit" class="btn btn-primary btn-sm">
                    Appliquer les filtres
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    Réinitialiser
                </a>
            </div>
        </form>

        {{-- Tableau des demandes --}}
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle" id="demandes-table">
                        <thead class="table-light">
                            <tr>
                                <th>Référence</th>
                                <th>Titre</th>
                                <th>Demandeur</th>
                                <th>Société demandeur</th>
                                <th>Société affectée</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($demandes as $demande)
                                <tr>
                                    <td>{{ $demande->reference }}</td>
                                    <td>{{ $demande->titre }}</td>
                                    <td>{{ $demande->user->nom ?? 'N/A' }}</td>
                                    <td>{{ $demande->societe ?? 'Non spécifiée' }}</td>
                                    <td>
                                        @if ($demande->societe_assignee)
                                            <span class="badge bg-success">
                                                {{ $demande->societe_assignee }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                Non affectée
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        @php $p = strtolower($demande->priorite); @endphp
                                        <span
                                            class="badge
                                        @if ($p === 'normale') bg-success
                                        @elseif($p === 'urgente') bg-warning text-dark
                                        @elseif($p === 'critique') bg-danger
                                        @else bg-secondary @endif
                                    ">
                                            {{ $demande->priorite }}
                                        </span>
                                    </td>
                                    <td>
                                        @php $s = strtolower($demande->statut); @endphp
                                        <span
                                            class="badge
                                        @if ($s === 'en attente') bg-warning text-dark
                                        @elseif($s === 'en cours') bg-info text-dark
                                        @elseif($s === 'à risque') bg-danger
                                        @elseif($s === 'clôturé') bg-success
                                        @else bg-secondary @endif
                                    ">
                                            {{ ucfirst($demande->statut) }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        {{-- Voir détails : simple lien vers la fiche --}}
                                        <a href="{{ route('demandes.show', $demande) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            Voir
                                        </a>

                                        {{-- Bouton affectation : ouvre le modal spécifique à cette demande --}}
                                        @if ($s !== 'clôturé' && $s !== 'en cours')
                                        <button type="button" class="btn btn-outline-secondary btn-sm ms-1"
                                            data-bs-toggle="modal" data-bs-target="#assignmentModal-{{ $demande->id }}">
                                            Affecter
                                        </button>
                                        @endif

                                        {{-- (optionnel) suppression / autre action --}}
                                        {{-- 
                                    <form method="POST" action="{{ route('admin.demandes.destroy', $demande) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Supprimer cette demande ?')">
                                            🗑
                                        </button>
                                    </form>
                                    --}}
                                    </td>
                                </tr>

                                {{-- Modal d'affectation pour cette demande --}}
                                <div class="modal fade" id="assignmentModal-{{ $demande->id }}" tabindex="-1"
                                    aria-labelledby="assignmentModalLabel-{{ $demande->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.assign-demande') }}">
                                                @csrf
                                                <input type="hidden" name="demande_id" value="{{ $demande->id }}">

                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="assignmentModalLabel-{{ $demande->id }}">
                                                        Affectation de la demande #{{ $demande->reference }}
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Fermer"></button>
                                                </div>

                                                <div class="modal-body">
                                                    <div class="mb-2">
                                                        <small class="text-muted">
                                                            <strong>Titre :</strong> {{ $demande->titre }}
                                                        </small>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Société demandeur</label>
                                                        <input type="text" class="form-control-plaintext"
                                                            value="{{ $demande->societe ?? 'Non spécifiée' }}" readonly>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="societe-{{ $demande->id }}" class="form-label">
                                                            Société affectée
                                                        </label>
                                                        <select name="societe" id="societe-{{ $demande->id }}"
                                                            class="form-select" required>
                                                            <option value="">-- Sélectionner une société --</option>
                                                            @foreach ($societes as $idSoc => $nomSoc)
                                                                <option value="{{ $nomSoc }}"
                                                                    {{ $demande->societe_assignee === $nomSoc ? 'selected' : '' }}>
                                                                    {{ $nomSoc }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="statut-{{ $demande->id }}" class="form-label">
                                                            Statut
                                                        </label>
                                                        <select name="statut" id="statut-{{ $demande->id }}"
                                                            class="form-select" required>
                                                            <option value="en attente"
                                                                {{ $demande->statut === 'en attente' ? 'selected' : '' }}>
                                                                En attente</option>
                                                            <option value="en cours"
                                                                {{ $demande->statut === 'en cours' ? 'selected' : '' }}>En
                                                                cours</option>
                                                            <option value="à risque"
                                                                {{ $demande->statut === 'à risque' ? 'selected' : '' }}>À
                                                                risque</option>
                                                            <option value="clôturé"
                                                                {{ $demande->statut === 'clôturé' ? 'selected' : '' }}>
                                                                Clôturé</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                                        data-bs-dismiss="modal">
                                                        Annuler
                                                    </button>
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-3 text-muted">
                                        Aucune demande trouvée.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- si tu as une pagination --}}
                @if (method_exists($demandes, 'links'))
                    <div class="card-footer">
                        {{ $demandes->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
