@extends('layouts.appAdministration')

@section('title', 'Modifier la demande - GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
      <h1>Modifier la demande #{{ $demande->id }}</h1>
      <button class="gida-btn" onclick="window.location.href='{{ route('dashboardEmployer') }}'">← Retour au tableau de bord</button>
    </div>

    <!-- FORMULAIRE DE MODIFICATION -->
    <section class="edit-demande-section" id="section-edit">
      <div class="form-container">
        
        <!-- Informations de la demande -->
        <div class="demande-summary">
          <h3>Demande: {{ $demande->titre }}</h3>
          <p><strong>Demandeur:</strong> {{ $demande->nom }} ({{ $demande->societe }})</p>
          <p><strong>Catégorie:</strong> {{ $demande->categorie }} | <strong>Priorité:</strong> {{ $demande->priorite }}</p>
        </div>

        <form class="edit-form" method="POST" action="{{ route('demandes.update', $demande->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <div class="form-group">
            <label for="titre">Titre de la demande *</label>
            <input type="text" id="titre" name="titre" value="{{ old('titre', $demande->titre) }}" required>
            @error('titre')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="categorie">Catégorie *</label>
            <select id="categorie" name="categorie" required>
              <option value="Informatique" {{ $demande->categorie == 'Informatique' ? 'selected' : '' }}>Informatique</option>
              <option value="RH" {{ $demande->categorie == 'RH' ? 'selected' : '' }}>RH</option>
              <option value="Finances" {{ $demande->categorie == 'Finances' ? 'selected' : '' }}>Finances</option>
              <option value="Juridique" {{ $demande->categorie == 'Juridique' ? 'selected' : '' }}>Juridique</option>
              <option value="Logistique" {{ $demande->categorie == 'Logistique' ? 'selected' : '' }}>Logistique</option>
              <option value="Marketing" {{ $demande->categorie == 'Marketing' ? 'selected' : '' }}>Marketing</option>
              <option value="Autre" {{ $demande->categorie == 'Autre' ? 'selected' : '' }}>Autre</option>
            </select>
            @error('categorie')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="priorite">Priorité *</label>
            <select id="priorite" name="priorite" required>
              <option value="Normale" {{ $demande->priorite == 'Normale' ? 'selected' : '' }}>Normale</option>
              <option value="Urgente" {{ $demande->priorite == 'Urgente' ? 'selected' : '' }}>Urgente</option>
              <option value="Critique" {{ $demande->priorite == 'Critique' ? 'selected' : '' }}>Critique</option>
            </select>
            @error('priorite')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="statut">Statut *</label>
            <select id="statut" name="statut" required>
              <option value="en attente" {{ $demande->statut == 'en attente' ? 'selected' : '' }}>En attente</option>
              <option value="en cours" {{ $demande->statut == 'en cours' ? 'selected' : '' }}>En cours</option>
              <option value="à risque" {{ $demande->statut == 'à risque' ? 'selected' : '' }}>À risque</option>
              <option value="clôturé" {{ $demande->statut == 'clôturé' ? 'selected' : '' }}>Clôturé</option>
            </select>
            @error('statut')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="date_limite">Date limite</label>
            <input type="date" id="date_limite" name="date_limite" value="{{ old('date_limite', $demande->date_limite ? $demande->date_limite->format('Y-m-d') : '') }}">
            @error('date_limite')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="description">Description *</label>
            <textarea id="description" name="description" required rows="5">{{ old('description', $demande->description) }}</textarea>
            @error('description')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="fichier">Pièce jointe (remplacer l'existante)</label>
            <input type="file" id="fichier" name="fichier" accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.html,.htm">
            @error('fichier')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-group">
            <label for="fichiers">Autres pièces jointes (multiples)</label>
            <input type="file" id="fichiers" name="fichiers[]" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.html,.htm">
            @error('fichiers.*')
              <span class="error">{{ $message }}</span>
            @enderror
          </div>

          <div class="form-actions">
            <button class="gida-btn gida-btn-primary" type="submit">Mettre à jour la demande</button>
            <button class="gida-btn gida-btn-secondary" type="button" onclick="window.location.href='{{ route('demandes.show', $demande->id) }}'">Annuler</button>
          </div>
        </form>

      </div>
    </section>

    <style>
      .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      }

      .demande-summary {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        border-left: 4px solid #007bff;
      }

      .demande-summary h3 {
        color: #333;
        margin-bottom: 1rem;
      }

      .demande-summary p {
        margin-bottom: 0.5rem;
        color: #555;
      }

      .form-group {
        margin-bottom: 1.5rem;
      }

      .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #333;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
      }

      .form-group textarea {
        resize: vertical;
        min-height: 100px;
      }

      .error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
      }

      .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid #eee;
      }

      .gida-btn-primary {
        background: #007bff;
        color: white;
      }

      .gida-btn-secondary {
        background: #6c757d;
        color: white;
      }

      .gida-btn:hover {
        opacity: 0.9;
      }
    </style>
@endsection
