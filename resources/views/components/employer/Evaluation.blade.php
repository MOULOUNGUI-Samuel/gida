@extends('layouts.appEmployer')

@section('title', 'Évaluation - GIDA')

@section('content')
    <!-- HEADER UTILISATEUR + CTA -->
    <div class="gida-header" id="gida-header">
        <h1>Évaluation de satisfaction</h1>
        <button class="gida-btn" onclick="window.location.href='{{ route('dashboardEmployer') }}'">
            ← Retour au tableau de bord
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- MODULE ÉVALUATION DE SATISFACTION -->
    <section class="gida-form-section" id="section-eval" aria-label="Évaluation satisfaction">
        <div class="evaluation-container">
            <div class="evaluation-header">
                <h2>Évaluation de la prise en charge</h2>
                <p class="evaluation-description">Merci de partager votre expérience pour nous aider à nous améliorer</p>
            </div>
            
            <form action="{{ route('evaluations.store') }}" method="POST" class="evaluation-form" id="evaluationForm">
                @csrf
                <input type="hidden" name="demande_id" value="{{ $demandeId ?? '' }}">
                
                <div class="rating-card">
                    <div class rating-section>
                        <label>Note globale : <span class="rating-value" id="ratingValue">0</span>/5</label>
                        <div class="gida-rating">
                            <input type="radio" name="note" value="5" id="star5" required>
                            <label for="star5" class="gida-star" data-value="5">★</label>
                            
                            <input type="radio" name="note" value="4" id="star4">
                            <label for="star4" class="gida-star" data-value="4">★</label>
                            
                            <input type="radio" name="note" value="3" id="star3">
                            <label for="star3" class="gida-star" data-value="3">★</label>
                            
                            <input type="radio" name="note" value="2" id="star2">
                            <label for="star2" class="gida-star" data-value="2">★</label>
                            
                            <input type="radio" name="note" value="1" id="star1">
                            <label for="star1" class="gida-star" data-value="1">★</label>
                        </div>
                        
                        <div class="rating-labels">
                            <span>Pas satisfait</span>
                            <span>Très satisfait</span>
                        </div>
                        
                        <div class="emoji-feedback">
                            <label for="star1" class="emoji" data-value="1">😠</label>
                            <label for="star2" class="emoji" data-value="2">😕</label>
                            <label for="star3" class="emoji" data-value="3">😐</label>
                            <label for="star4" class="emoji" data-value="4">😊</label>
                            <label for="star5" class="emoji" data-value="5">😍</label>
                        </div>
                        
                        @error('note')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-card">
                    <div class="form-group">
                        <label for="commentaire">Commentaire (facultatif) :</label>
                        <textarea id="commentaire" name="commentaire" 
                            placeholder="Partagez votre expérience, vos suggestions..." 
                            rows="4"
                            maxlength="1000">{{ old('commentaire') }}</textarea>
                        <div class="character-count">
                            <span id="charCount">0</span>/1000 caractères
                        </div>
                        @error('commentaire')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="gida-btn gida-btn-primary">
                        <span class="submit-text">Valider l'évaluation</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #7209b7;
            --success: #06d6a0;
            --warning: #ffd166;
            --danger: #ef476f;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --border-radius: 12px;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

        .evaluation-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .evaluation-header {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 25px;
            text-align: center;
        }

        .evaluation-header h2 {
            font-size: 1.8rem;
            margin-bottom: 10px;
        }

        .evaluation-description {
            opacity: 0.9;
        }

        .evaluation-form {
            padding: 30px;
        }

        .rating-card, .form-card {
            background: var(--light);
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 25px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .alert-success {
            background-color: #e6f7f2;
            color: #0a7b5c;
            border-left: 4px solid var(--success);
        }
        
        .alert-danger {
            background-color: #fde8ec;
            color: #b91c43;
            border-left: 4px solid var(--danger);
        }
        
        .alert i {
            font-size: 1.2rem;
        }

        .error-message {
            color: var(--danger);
            font-size: 0.9rem;
            margin-top: 0.5rem;
            display: block;
        }
        
        .character-count {
            text-align: right;
            font-size: 0.8rem;
            color: var(--gray);
            margin-top: 0.5rem;
        }
        
        .rating-value {
            font-weight: bold;
            color: var(--primary);
            font-size: 1.2rem;
        }
        
        .rating-section {
            text-align: center;
        }

        .rating-section label {
            display: block;
            margin-bottom: 1.5rem;
            font-weight: 600;
            color: var(--dark);
            font-size: 1.1rem;
        }

        .gida-rating {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .gida-rating input[type="radio"] {
            display: none;
        }

        .gida-star {
            cursor: pointer;
            color: #ddd;
            transition: var(--transition);
        }

        .gida-star:hover,
        .gida-rating input[type="radio"]:checked ~ .gida-star,
        .gida-rating input[type="radio"]:checked + .gida-star {
            color: #ffd700;
            transform: scale(1.1);
        }

        .rating-labels {
            display: flex;
            justify-content: space-between;
            margin: 1rem 0;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .emoji-feedback {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .emoji {
            font-size: 2rem;
            cursor: pointer;
            transition: var(--transition);
            opacity: 0.6;
        }

        .emoji:hover,
        .gida-rating input[type="radio"]:checked ~ .emoji,
        .gida-rating input[type="radio"]:checked + .emoji {
            transform: scale(1.2);
            opacity: 1;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: var(--dark);
        }

        .form-group textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
            transition: var(--transition);
        }

        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }

        .form-actions {
            text-align: center;
            margin-top: 2rem;
        }

        .gida-btn-primary {
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            padding: 12px 35px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(67, 97, 238, 0.3);
        }

        .gida-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(67, 97, 238, 0.4);
        }

        .spinner-border {
            vertical-align: middle;
        }
        
        .submit-text {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .evaluation-container {
                margin: 10px;
            }
            
            .evaluation-form {
                padding: 20px;
            }
            
            .rating-card, .form-card {
                padding: 20px;
            }
            
            .gida-rating {
                font-size: 2rem;
            }
            
            .emoji {
                font-size: 1.7rem;
            }
        }
    </style>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('evaluationForm');
            const stars = document.querySelectorAll('.gida-star');
            const emojis = document.querySelectorAll('.emoji');
            const ratingValue = document.getElementById('ratingValue');
            const commentaire = document.getElementById('commentaire');
            const charCount = document.getElementById('charCount');
            const submitBtn = form.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            const submitText = submitBtn.querySelector('.submit-text');
            
            // Mettre à jour le compteur de caractères
            if (commentaire) {
                charCount.textContent = commentaire.value.length;
                
                commentaire.addEventListener('input', function() {
                    charCount.textContent = this.value.length;
                });
            }
            
            // Gestion de la sélection des étoiles
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    ratingValue.textContent = value;
                    updateRatingDisplay(value);
                });
            });
            
            // Gestion de la sélection des emojis
            emojis.forEach(emoji => {
                emoji.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    document.getElementById(`star${value}`).checked = true;
                    ratingValue.textContent = value;
                    updateRatingDisplay(value);
                });
            });
            
            function updateRatingDisplay(value) {
                // Mettre à jour l'affichage des étoiles
                stars.forEach(star => {
                    if (star.getAttribute('data-value') <= value) {
                        star.style.color = '#ffd700';
                    } else {
                        star.style.color = '#ddd';
                    }
                });
                
                // Mettre à jour l'affichage des emojis
                emojis.forEach(emoji => {
                    if (emoji.getAttribute('data-value') === value) {
                        emoji.style.opacity = '1';
                        emoji.style.transform = 'scale(1.2)';
                    } else {
                        emoji.style.opacity = '0.6';
                        emoji.style.transform = 'scale(1)';
                    }
                });
                
                // Mettre à jour la couleur de la valeur
                const colors = {
                    1: "#ef476f",
                    2: "#ffb74d", 
                    3: "#ffee58",
                    4: "#42a5f5",
                    5: "#06d6a0"
                };
                ratingValue.style.color = colors[value];
            }
            
            // Soumission du formulaire
            form.addEventListener('submit', function() {
                // Désactiver le bouton et afficher le spinner
                submitBtn.disabled = true;
                spinner.classList.remove('d-none');
                submitText.textContent = 'Envoi en cours...';
            });
            
            // Pré-remplir avec d'anciennes valeurs en cas d'erreur
            @if(old('note'))
                const selectedValue = "{{ old('note') }}";
                document.getElementById(`star${selectedValue}`).checked = true;
                ratingValue.textContent = selectedValue;
                updateRatingDisplay(selectedValue);
            @endif
        });
    </script>
    @endpush
@endsection