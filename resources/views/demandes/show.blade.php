@extends(Auth::user()->type == 1 ? 'layouts.appEmployer' : 'layouts.appAdministration')

@section('title', 'Détails de la demande - GIDA')

@section('content')
<div class="container my-4">

    {{-- HEADER UTILISATEUR + CTA --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 mb-0">Détails de la demande #{{ $demande->id }}</h1>

        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
            &larr; Retour
        </a>
        
    </div>

    {{-- DÉTAILS DE LA DEMANDE --}}
    <section class="demande-details" id="section-details">
        <div class="card shadow-sm mb-4">
            <div class="card-body">

                {{-- Titre de la demande --}}
                <div class="demande-header mb-4">
                    <h2 class="h5 mb-1">{{ $demande->titre }}</h2>
                    {{-- Exemple si tu veux ré-afficher la référence / date:
                    <div class="small text-muted">
                        Référence: TK-{{ str_pad($demande->id, 5, '0', STR_PAD_LEFT) }} |
                        Créée le: {{ $demande->formatted_created_at }}
                    </div>
                    --}}
                </div>

                {{-- Grille d'informations --}}
                <div class="row g-4 mb-3">

                    {{-- Colonne gauche : infos demande --}}
                    <div class="col-md-7">
                        <h3 class="h6 border-bottom pb-2 mb-3">Informations de la demande</h3>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Catégorie :</label>
                            <div>{{ $demande->categorie }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Priorité :</label>
                            <div>
                                <span class="badge
                                    @if(strtolower($demande->priorite) === 'normale') bg-success
                                    @elseif(strtolower($demande->priorite) === 'urgente') bg-warning text-dark
                                    @elseif(strtolower($demande->priorite) === 'critique') bg-danger
                                    @else bg-secondary
                                    @endif
                                ">
                                    {{ $demande->priorite }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Date limite :</label>
                            <div>{{ $demande->date_limite ? $demande->date_limite->format('d/m/Y') : 'Non définie' }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Description :</label>
                            <div class="bg-light border-start border-primary border-4 rounded py-2 px-3">
                                <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">{{ $demande->description }}</pre>
                            </div>
                        </div>

                        {{-- Progression du traitement --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Progression du traitement :</label>

                            <div class="progress-container mt-2">
                                @php
                                    $statuses = [
                                        'en attente' => ['icon' => '⏳', 'class' => 'status-pending'],
                                        'en cours'   => ['icon' => '🔄', 'class' => 'status-in-progress'],
                                        'à risque'   => ['icon' => '⚠️', 'class' => 'status-at-risk'],
                                        'clôturé'    => ['icon' => '✅', 'class' => 'status-completed'],
                                    ];
                                    $currentStatus = !empty($demande->statut) ? strtolower($demande->statut) : 'en attente';
                                    $currentIndex  = array_search($currentStatus, array_keys($statuses));
                                    $progress      = $currentIndex !== false ? (($currentIndex + 1) / count($statuses)) * 100 : 0;
                                @endphp

                                <div class="progress-track mb-3">
                                    @foreach($statuses as $status => $data)
                                        @php
                                            $isActive  = array_search($status, array_keys($statuses)) <= $currentIndex;
                                            $isCurrent = $status === $currentStatus;
                                        @endphp

                                        <div class="progress-step {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                            <div class="step-icon {{ $data['class'] }}">
                                                {{ $data['icon'] }}
                                            </div>
                                            <div class="step-label">{{ ucfirst($status) }}</div>
                                        </div>

                                        @if(!$loop->last)
                                            <div class="progress-connector">
                                                <div class="progress-line {{ $isActive ? 'active' : '' }}" style="width: {{ $isActive ? '100%' : '0%' }}"></div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="progress-bar-container mb-2">
                                    <div class="progress" style="height: 10px;">
                                        <div
                                            class="progress-bar
                                                @if($currentStatus === 'en attente') bg-warning
                                                @elseif($currentStatus === 'en cours') bg-info
                                                @elseif($currentStatus === 'à risque') bg-danger
                                                @elseif($currentStatus === 'clôturé') bg-success
                                                @else bg-secondary
                                                @endif
                                            "
                                            role="progressbar"
                                            style="width: {{ $progress }}%;"
                                            aria-valuenow="{{ round($progress) }}"
                                            aria-valuemin="0"
                                            aria-valuemax="100"
                                            id="progress-bar"
                                        ></div>
                                    </div>
                                    <div class="text-end small text-muted mt-1">
                                        {{ round($progress) }}%
                                    </div>
                                </div>

                                <div class="current-status text-center mt-2">
                                    <span class="badge rounded-pill
                                        @if($currentStatus === 'en attente') bg-warning text-dark
                                        @elseif($currentStatus === 'en cours') bg-info text-dark
                                        @elseif($currentStatus === 'à risque') bg-danger
                                        @elseif($currentStatus === 'clôturé') bg-success
                                        @else bg-secondary
                                        @endif
                                        status
                                    ">
                                        {{ $statuses[$currentStatus]['icon'] ?? '⏳' }}
                                        Statut actuel : {{ ucfirst($currentStatus) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        @if($demande->infos_supplementaires)
                            <div class="mb-3">
                                <label class="form-label fw-semibold mb-1">Informations supplémentaires :</label>
                                <div class="bg-light rounded py-2 px-3 border">
                                    <pre class="mb-0" style="white-space: pre-wrap; font-family: inherit;">
{{ $demande->infos_supplementaires }}</pre>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Colonne droite : infos demandeur --}}
                    <div class="col-md-5">
                        <h3 class="h6 border-bottom pb-2 mb-3">Informations du demandeur</h3>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Nom :</label>
                            <div>{{ $demande->nom }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Société :</label>
                            <div class="fw-semibold">
                                @if($demande->user->societe)
                                    {{ $demande->user->societe }}
                                @elseif($demande->user->entreprise)
                                    {{ $demande->user->entreprise->nom }}
                                @else
                                    <span class="text-muted">Entreprise non définie</span>
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold mb-1">Email :</label>
                            <div>{{ $demande->mail }}</div>
                        </div>
                    </div>
                </div>

                {{-- Pièces jointes --}}
                @if($demande->fichier || ($demande->piecesJointes && $demande->piecesJointes->count()))
                    <div class="mt-3">
                        <h3 class="h6 border-bottom pb-2 mb-3">Pièces jointes</h3>

                        <div class="list-group mb-3">
                            @if($demande->fichier)
                                <a href="#" onclick="openFileModal('{{ asset('storage/' . $demande->fichier) }}')" class="list-group-item list-group-item-action d-flex align-items-center">
                                    <span class="me-2">📂</span> Voir la pièce jointe
                                </a>
                            @endif

                            @if($demande->piecesJointes && $demande->piecesJointes->count())
                                @foreach($demande->piecesJointes as $pj)
                                    <a href="#" onclick="openFileModal('{{ asset('storage/' . $pj->chemin) }}')" class="list-group-item list-group-item-action d-flex align-items-center">
                                        <span class="me-2">📎</span> {{ $pj->nom_fichier }}
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Modal Bootstrap pour l'aperçu du fichier --}}
                    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="fileModalLabel">Aperçu du fichier</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                </div>
                                <div class="modal-body p-0" style="height: 80vh;">
                                    <iframe id="fileViewer" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <a id="downloadFile" href="" class="btn btn-primary" download>
                                        Télécharger
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        Fermer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        function openFileModal(fileUrl) {
                            const fileViewer   = document.getElementById('fileViewer');
                            const downloadLink = document.getElementById('downloadFile');

                            fileViewer.src     = fileUrl;
                            downloadLink.href  = fileUrl;

                            const modal = new bootstrap.Modal(document.getElementById('fileModal'));
                            modal.show();
                        }
                    </script>
                @endif

                {{-- Actions générales (bas de la fiche) --}}
                <div class="demande-actions border-top pt-3 mt-4">
                    @if(auth()->user()->type == 0) {{-- Admin --}}
                        <div class="admin-actions">
                            <h3 class="h6 mb-3">Actions administrateur</h3>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('demandes.edit', $demande->id) }}" class="btn btn-primary btn-sm">
                                    Modifier le statut
                                </a>
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                    Retour à la liste
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="user-actions">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                    Retour au tableau de bord
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            </div> {{-- card-body --}}
        </div> {{-- card --}}
    </section>

    {{-- BLOC DE TRAITEMENT DE LA DEMANDE (Administrateur / Entreprise Support) --}}
    @if(in_array(Auth::user()->type, [0, 2]) && strtolower($demande->statut) !== 'clôturé')
        <section class="mt-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h3 class="h6 mb-2">Traitement de la demande</h3>
                    <p class="small text-muted mb-3">
                        Mettez à jour le statut de la demande et ajoutez des informations sur le traitement effectué.
                    </p>

                    <form id="form-traitement-demande" action="{{ route('demandes.updateStatus', $demande) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-4">
                            <label for="statut" class="form-label">Statut de la demande *</label>
                            @php
                                $statutActuel = strtolower($demande->statut ?? 'en attente');
                            @endphp
                            <select name="statut" id="statut" class="form-select" required>
                                <option value="en attente" {{ $statutActuel === 'en attente' ? 'selected' : '' }}>En attente</option>
                                <option value="en cours" {{ $statutActuel === 'en cours' ? 'selected' : '' }}>En cours</option>
                                <option value="à risque" {{ $statutActuel === 'à risque' ? 'selected' : '' }}>À risque</option>
                                <option value="clôturé" {{ $statutActuel === 'clôturé' ? 'selected' : '' }}>Clôturé</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="infos_supplementaires" class="form-label">Informations de traitement / commentaire</label>
                            <textarea
                                name="infos_supplementaires"
                                id="infos_supplementaires"
                                rows="4"
                                class="form-control"
                                placeholder="Indiquez les actions réalisées, les difficultés rencontrées, ou les prochaines étapes..."
                            >{{ old('infos_supplementaires', $demande->infos_supplementaires) }}</textarea>
                        </div>

                        <div class="col-12 d-flex align-items-center">
                            <button type="submit" class="btn btn-success">
                                Enregistrer le traitement
                            </button>
                            <small id="traitement-message" class="ms-3 small"></small>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    @endif

</div> {{-- .container --}}

{{-- JS traitement AJAX --}}
<script>
    const formTraitement = document.getElementById('form-traitement-demande');
    if (formTraitement) {
        formTraitement.addEventListener('submit', function (e) {
            e.preventDefault();

            const url       = this.action;
            const formData  = new FormData(this);
            const messageEl = document.getElementById('traitement-message');

            messageEl.classList.remove('text-success', 'text-danger');
            messageEl.classList.add('text-muted');
            messageEl.textContent = 'Enregistrement en cours...';

            fetch(url, {
                method: 'POST', // @method('PATCH') est inclus dans le formData
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                messageEl.classList.remove('text-muted');

                if (data.success) {
                    messageEl.classList.add('text-success');
                    messageEl.textContent = data.message || 'Traitement enregistré.';

                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    messageEl.classList.add('text-danger');
                    messageEl.textContent = data.message || 'Une erreur est survenue.';
                }
            })
            .catch(err => {
                console.error(err);
                messageEl.classList.remove('text-muted');
                messageEl.classList.add('text-danger');
                messageEl.textContent = 'Erreur réseau, veuillez réessayer.';
            });
        });
    }
</script>

{{-- Laravel Echo / temps réel --}}
<script src="{{ asset('js/app.js') }}"></script>
<script>
    Echo.private(`demande.{{ $demande->id }}`)
        .listen('DemandeUpdated', (e) => {
            const statusEl = document.querySelector('.status');
            if (statusEl) {
                statusEl.innerText = e.statut;
            }
            const bar = document.getElementById('progress-bar');
            if (bar && typeof e.progress !== 'undefined') {
                bar.style.width = e.progress + '%';
                bar.setAttribute('aria-valuenow', e.progress);
            }
        });
</script>

{{-- Styles spécifiques (timeline) --}}
<style>
    .progress-track {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    .progress-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }
    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
        background: #f0f0f0;
        border: 2px solid #ddd;
        font-size: 18px;
        transition: all 0.3s ease;
    }
    .progress-step.active .step-icon {
        background: #e3f2fd;
        border-color: #0d6efd;
        color: #0d47a1;
    }
    .progress-step.current .step-icon {
        transform: scale(1.1);
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.3);
    }
    .step-label {
        font-size: 0.75rem;
        text-align: center;
        color: #6c757d;
        margin-top: 4px;
    }
    .progress-connector {
        flex: 1;
        height: 3px;
        background: #e0e0e0;
        position: relative;
        margin: 0 5px;
    }
    .progress-line {
        height: 100%;
        background: #0d6efd;
        transition: width 0.5s ease;
    }

    @media (max-width: 768px) {
        .progress-track {
            flex-direction: column;
            gap: 0.5rem;
        }
        .progress-connector {
            display: none;
        }
    }
</style>
@endsection
