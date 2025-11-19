@extends('layouts.appAdministration')

@section('title', 'Supervision & Contrôle Qualité - GIDA')

@section('content')
    <!-- Header -->
    <div class="admin-header">
        <h1>Supervision & Contrôle Qualité</h1>
        <div>
            <button class="admin-btn" onclick="window.location.href='{{ route('dashboard') }}'">← Retour au dashboard</button>
        </div>
    </div>

    @if(isset($demande))
        <!-- Détails du ticket -->
        <div class="ticket-details-card">
            <div class="ticket-header">
                <h2>Contrôle Qualité - Ticket #{{ $demande->id }}</h2>
                <div class="ticket-status">
                    <span class="status-badge status-{{ $demande->status_class }}">
                        {{ ucfirst($demande->statut) }}
                    </span>
                </div>
            </div>
            
            <div class="ticket-info-grid">
                <div class="info-section">
                    <h4>Informations générales</h4>
                    <p><strong>Titre :</strong> {{ $demande->titre }}</p>
                    <p><strong>Demandeur :</strong> {{ $demande->nom }}</p>
                    <p><strong>Email :</strong> {{ $demande->email }}</p>
                    <p><strong>Société assignée :</strong> {{ $demande->societe ?? 'Non assignée' }}</p>
                    <p><strong>Priorité :</strong> 
                        <span class="priority-{{ strtolower($demande->priorite) }}">
                            {{ $demande->priorite }}
                        </span>
                    </p>
                    <p><strong>Date de création :</strong> {{ $demande->created_at->format('d/m/Y H:i') }}</p>
                </div>
                
                <div class="info-section">
                    <h4>Description de la demande</h4>
                    <div class="description-box">
                        {{ $demande->description }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Réponse de la société -->
        <div class="response-section">
            <h3>Réponse/Solution proposée</h3>
            <div class="response-box">
                @if($demande->reponse)
                    <p><strong>Réponse :</strong></p>
                    <div class="response-content">
                        {{ $demande->reponse }}
                    </div>
                    <p class="response-meta">
                        <strong>Date de réponse :</strong> {{ $demande->updated_at->format('d/m/Y H:i') }}
                    </p>
                @else
                    <div class="no-response">
                        <p>⏳ Aucune réponse fournie par la société assignée</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Évaluation des performances -->
        <div class="performance-section">
            <h3>Évaluation des performances</h3>
            <div class="performance-grid">
                <div class="perf-item">
                    <label>Délai de traitement</label>
                    <span class="perf-value">
                        @php
                            $delai = $demande->created_at->diffInHours($demande->updated_at);
                        @endphp
                        {{ $delai }}h
                        @if($delai > 48)
                            <span class="delay-warning">⚠️ Retard</span>
                        @else
                            <span class="delay-ok">✅ Dans les temps</span>
                        @endif
                    </span>
                </div>
                <div class="perf-item">
                    <label>Société responsable</label>
                    <span class="perf-value">{{ $demande->societe ?? 'Non assignée' }}</span>
                </div>
            </div>
        </div>

        <!-- Contrôle qualité -->
        <div class="quality-control-section">
            <h3>Validation et contrôle qualité</h3>
            <form id="quality-control-form">
                <input type="hidden" name="demande_id" value="{{ $demande->id }}">
                
                <div class="form-group">
                    <label for="quality-rating"><strong>Évaluation de la qualité</strong></label>
                    <select id="quality-rating" name="quality_rating" class="form-control" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="excellent">Excellent</option>
                        <option value="bon">Bon</option>
                        <option value="moyen">Moyen</option>
                        <option value="insuffisant">Insuffisant</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="validation-comments"><strong>Commentaires de validation</strong></label>
                    <textarea id="validation-comments" name="validation_comments" class="form-control" rows="4" 
                              placeholder="Ajoutez vos commentaires sur la qualité de la réponse, les points d'amélioration, etc."></textarea>
                </div>

                <div class="form-group">
                    <label for="customer-satisfaction"><strong>Satisfaction du demandeur</strong></label>
                    <select id="customer-satisfaction" name="customer_satisfaction" class="form-control">
                        <option value="">-- Non évaluée --</option>
                        <option value="tres_satisfait">Très satisfait</option>
                        <option value="satisfait">Satisfait</option>
                        <option value="peu_satisfait">Peu satisfait</option>
                        <option value="insatisfait">Insatisfait</option>
                    </select>
                </div>

                <div class="action-buttons">
                    <button type="button" class="btn btn-success" onclick="approveTicket()">
                        ✅ Approuver et clôturer
                    </button>
                    <button type="button" class="btn btn-warning" onclick="requestModifications()">
                        🔄 Demander des modifications
                    </button>
                    <button type="button" class="btn btn-danger" onclick="rejectTicket()">
                        ❌ Rejeter la clôture
                    </button>
                </div>
            </form>
        </div>

    @else
        <!-- Message d'instruction -->
        <div class="instruction-message">
            <div class="instruction-content">
                <h3>🎯 Contrôle Qualité</h3>
                <p>Sélectionnez un ticket depuis le Dashboard pour accéder au contrôle qualité.</p>
                <div class="instruction-actions">
                    <button class="admin-btn" onclick="window.location.href='{{ route('dashboard') }}'">
                        Aller au Dashboard
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        .ticket-details-card {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .ticket-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .ticket-info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .info-section h4 {
            color: #495057;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .info-section p {
            margin-bottom: 0.5rem;
            line-height: 1.5;
        }
        
        .description-box {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 6px;
            border-left: 4px solid #007bff;
            min-height: 100px;
        }
        
        .response-section, .performance-section, .quality-control-section {
            background: white;
            border-radius: 8px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .response-box {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 6px;
            margin-top: 1rem;
        }
        
        .response-content {
            background: white;
            padding: 1rem;
            border-radius: 4px;
            margin: 1rem 0;
            border-left: 4px solid #28a745;
        }
        
        .no-response {
            text-align: center;
            color: #6c757d;
            font-style: italic;
        }
        
        .performance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }
        
        .perf-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            background: #f8f9fa;
            border-radius: 6px;
        }
        
        .delay-warning {
            color: #dc3545;
            font-weight: bold;
        }
        
        .delay-ok {
            color: #28a745;
            font-weight: bold;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e9ecef;
        }
        
        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s;
            font-weight: 500;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-warning:hover {
            background: #e0a800;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
        }
        
        .priority-normale {
            color: #28a745;
            font-weight: bold;
        }
        
        .priority-urgente {
            color: #ffc107;
            font-weight: bold;
        }
        
        .priority-critique {
            color: #dc3545;
            font-weight: bold;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .instruction-message {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 400px;
            padding: 2rem;
        }
        
        .instruction-content {
            text-align: center;
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 3rem;
            max-width: 500px;
            width: 100%;
        }
        
        .instruction-content h3 {
            color: #495057;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }
        
        .instruction-content p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        
        .admin-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .admin-btn:hover {
            background: #0056b3;
        }
    </style>

    <script>
        function approveTicket() {
            if (!validateForm()) return;
            
            if (confirm('Êtes-vous sûr de vouloir approuver et clôturer ce ticket ?')) {
                submitQualityControl('approved');
            }
        }
        
        function requestModifications() {
            if (!validateForm()) return;
            
            if (confirm('Demander des modifications à la société assignée ?')) {
                submitQualityControl('modifications_requested');
            }
        }
        
        function rejectTicket() {
            if (!validateForm()) return;
            
            if (confirm('Êtes-vous sûr de vouloir rejeter la clôture de ce ticket ?')) {
                submitQualityControl('rejected');
            }
        }
        
        function validateForm() {
            const rating = document.getElementById('quality-rating').value;
            const comments = document.getElementById('validation-comments').value;
            
            if (!rating) {
                alert('Veuillez sélectionner une évaluation de la qualité');
                return false;
            }
            
            if (!comments.trim()) {
                alert('Veuillez ajouter des commentaires de validation');
                return false;
            }
            
            return true;
        }
        
        function submitQualityControl(action) {
            const formData = {
                demande_id: document.querySelector('input[name="demande_id"]').value,
                quality_rating: document.getElementById('quality-rating').value,
                validation_comments: document.getElementById('validation-comments').value,
                customer_satisfaction: document.getElementById('customer-satisfaction').value,
                action: action
            };
            
            fetch('/admin/quality-control', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Contrôle qualité enregistré avec succès');
                    window.location.href = '{{ route("dashboard") }}';
                } else {
                    alert('Erreur : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Erreur lors de l\'enregistrement');
            });
        }
    </script>
@endsection
