<!doctype html>
<html lang="fr">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="GFA-GA - Gestion des  - ">
    <meta name="keywords" content="GFA-GA, Gestion des , , Espace Proprietaire">
    <meta name="author" content="GFA-GA Team">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/bs-stepper/css/bs-stepper.css') }}" rel="stylesheet" />
    <!-- loader-->
    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    <title>GFA-GA</title>
</head>
<style>
    /*
 * Style du Composant d'Upload d'Image
 * Utilise les variables de la palette de couleurs recommandée
 */
:root {
    --color-primary: #2B6CB0;      /* Bleu Confiance */
    --color-text: #2D3748;         /* Gris Profond */
    --color-background: #F7FAFC;   /* Gris Clair */
    --color-border: #E2E8F0;       /* Gris Bordure */
    --color-danger: #C53030;        /* Rouge Erreur */
}

.image-uploader {
    position: relative;
    border: 2px dashed var(--color-border);
    border-radius: 8px;
    background-color: var(--color-background);
    transition: all 0.3s ease;
    overflow: hidden; /* Important pour les coins arrondis */
}

.image-uploader:hover {
    border-color: var(--color-primary);
    background-color: #fff;
}

/* On cache l'input par défaut */
.image-uploader input[type="file"] {
    display: none;
}

/* Style de la zone de clic */
.image-uploader .uploader-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 2rem;
    cursor: pointer;
    color: var(--color-text);
    text-align: center;
}

.image-uploader .uploader-content svg {
    width: 40px;
    height: 40px;
    stroke: var(--color-primary);
    margin-bottom: 0.75rem;
}

.image-uploader .uploader-content span {
    font-size: 1rem;
}

.image-uploader .uploader-content strong {
    color: var(--color-primary);
    font-weight: 600;
}

.image-uploader .uploader-content .uploader-constraints {
    font-size: 0.8rem;
    color: #718096; /* Gris moyen */
    margin-top: 0.25rem;
}

/* Style de la zone de prévisualisation */
.image-uploader .image-preview-container {
    position: relative; /* Contexte pour le bouton de suppression */
    display: none; /* Caché par défaut */
    width: 100%;
    height: 80px;
    padding: 0.5rem; /* Petite marge intérieure */
}

.image-uploader .image-preview {
    width: 100%;
    height: 80px;
    max-height: 80px; /* Limite la hauteur de l'aperçu */
    object-fit: contain; /* Assure que le logo est visible entièrement */
    border-radius: 4px;
}

/* Style du bouton de suppression */
.image-uploader .delete-image-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 28px;
    height: 28px;
    background-color: rgba(0, 0, 0, 0.6);
    color: white;
    border: none;
    border-radius: 50%;
    font-size: 18px;
    font-weight: bold;
    line-height: 28px; /* Centre la croix verticalement */
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 0;
}

.image-uploader .delete-image-btn:hover {
    background-color: var(--color-danger);
    transform: scale(1.1);
}
</style>
<body class="">
    <!--wrapper-->
    <div class="wrapper">
        <div class="section-authentication-cover">
            <div class="">
                <div class="row g-0">

                    <div
                        class="col-12 col-xl-7 col-xxl-7 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">
                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
                            <div class="card-body">
                                <img src="{{ asset('assets/images/login-images/register-cover.svg') }}"
                                    class="img-fluid auth-img-cover-login" width="650" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-xl-5 col-xxl-5 auth-cover-right align-items-center justify-content-center">
                        <div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
                            <div class="card-body">
                                <div class="">
                                    <div class="mb-2 text-center">
                                        <img src="{{ asset('assets/images/logo-icon.png') }}" width="60"
                                            alt="">
                                    </div>
                                    <div class="text-center mb-2">
                                        <h5 class="">Création du compte proprietaire</h5>
                                    </div>
                                    <div class="form-body">
                                        @if ($errors->any())
                                            <div class="alert alert-light-border-danger d-flex align-items-center justify-content-between"
                                                role="alert">
                                                <p class="mb-0 text-danger">
                                                    <i class="ti ti-alert-circle f-s-18 me-2"></i>
                                                    {{ $errors->first() }}
                                                </p>
                                                <i class="ti ti-x" data-bs-dismiss="alert"></i>
                                            </div>
                                        @endif
                                        @if (session('success'))
                                            <div
                                                class="alert alert-light-border-success d-flex align-items-center justify-content-between">
                                                <p class="mb-0 text-success">
                                                    <i class="ti ti-circle-check f-s-18 me-2"></i>
                                                    {{ session('success') }}
                                                </p>
                                                <i class="ti ti-x" data-bs-dismiss="alert"></i>
                                            </div>
                                        @endif
                                        <div id="stepper1" class="bs-stepper">
                                            <div class="card-header">
                                                <div class="d-lg-flex flex-lg-row align-items-lg-center justify-content-lg-between"
                                                    role="tablist">
                                                    <div class="step" data-target="#test-l-1">
                                                        <div class="step-trigger" role="tab" id="stepper1trigger1"
                                                            aria-controls="test-l-1">
                                                            <div class="bs-stepper-circle">1</div>
                                                            <div class="">
                                                                <h5 class="mb-0 steper-title">Structure</h5>
                                                                <p class="mb-0 steper-sub-title">Détails de la structure
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bs-stepper-line"></div>
                                                    <div class="step" data-target="#test-l-2">
                                                        <div class="step-trigger" role="tab" id="stepper1trigger2"
                                                            aria-controls="test-l-2">
                                                            <div class="bs-stepper-circle">2</div>
                                                            <div class="">
                                                                <h5 class="mb-0 steper-title">Propriétaire</h5>
                                                                <p class="mb-0 steper-sub-title">Détails du compte</p>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">

                                                <div class="bs-stepper-content">
                                                    <form method="POST" {{ route('login') }}
                                                        class="row g-3 app-form needs-validation" novalidate
                                                        onSubmit="return false">
                                                        @csrf
                                                        <div id="test-l-1" role="tabpanel" class="bs-stepper-pane"
                                                            aria-labelledby="stepper1trigger1">
                                                            <h5 class="mb-1">Informations de la structure</h5>
                                                            <p class="mb-4">Veuillez renseigner les informations de
                                                                votre structure</p>

                                                            <div class="row g-3">
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructureName" class="form-label">Nom
                                                                        de la structure <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control shadow-sm"
                                                                        id="StructureName"
                                                                        placeholder="Nom de la structure" required>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructureType" class="form-label">Type
                                                                        de structure <span
                                                                            class="text-danger">*</span></label>
                                                                    <select class="form-select shadow-sm"
                                                                        id="StructureType"
                                                                        aria-label="Default select example" required>
                                                                        <option value="" selected>---</option>
                                                                        <option value="1">Entreprise</option>
                                                                        <option value="2">Association</option>
                                                                        <option value="3">Organisation</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructureAddress"
                                                                        class="form-label">Adresse <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control shadow-sm"
                                                                        id="StructureAddress" placeholder="Adresse"
                                                                        required>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructureCity"
                                                                        class="form-label">Ville <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control shadow-sm"
                                                                        id="StructureCity" placeholder="Ville"
                                                                        required>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructureCountry"
                                                                        class="form-label">Pays <span
                                                                            class="text-danger">*</span></label>
                                                                    <select class="form-select shadow-sm"
                                                                        id="StructureCountry"
                                                                        aria-label="Default select example" required>
                                                                        <option value="" selected>---</option>
                                                                        <option value="1">France</option>
                                                                        <option value="2">Belgique</option>
                                                                        <option value="3">Suisse</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="StructurePhone"
                                                                        class="form-label">Numéro de téléphone <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control shadow-sm"
                                                                        id="StructurePhone"
                                                                        placeholder="Numéro de téléphone" required>
                                                                </div>
                                                                <!-- Intégrez ce bloc dans votre formulaire -->
                                                                <div class="col-12">
                                                                    <label class="form-label">Votre logo <span
                                                                            class="text-danger">*</span></label>

                                                                    <!-- Le composant d'upload -->
                                                                    <div id="image-uploader" class="image-uploader">
                                                                        <!-- 1. L'input de fichier, caché par le CSS -->
                                                                        <input id="logo-upload" type="file"
                                                                            name="logo" accept=".png, .jpg, .jpeg">

                                                                        <!-- 2. Le contenu de l'état initial (zone de clic) -->
                                                                        <label for="logo-upload"
                                                                            class="uploader-content">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                width="32" height="32"
                                                                                viewBox="0 0 24 24" fill="none"
                                                                                stroke="currentColor" stroke-width="2"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                class="feather feather-upload-cloud">
                                                                                <path
                                                                                    d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z">
                                                                                </path>
                                                                                <polyline points="16 16 12 12 8 16">
                                                                                </polyline>
                                                                                <line x1="12" y1="12"
                                                                                    x2="12" y2="21">
                                                                                </line>
                                                                            </svg>
                                                                            <span>Glissez-déposez ou <strong>cliquez
                                                                                    ici</strong></span>
                                                                            <span class="uploader-constraints">PNG, JPG
                                                                                ou JPEG - Max 2Mo</span>
                                                                        </label>

                                                                        <!-- 3. La zone de prévisualisation de l'image -->
                                                                        <div class="image-preview-container">
                                                                            <img id="image-preview" src="#"
                                                                                alt="Aperçu du logo"
                                                                                class="image-preview">
                                                                            <button type="button"
                                                                                id="delete-image-btn"
                                                                                class="delete-image-btn"
                                                                                aria-label="Supprimer l'image">×</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <button class="btn btn-primary px-4"
                                                                        id="nextButton" disabled
                                                                        onclick="stepper1.next()">Suivant<i
                                                                            class='bx bx-right-arrow-alt ms-2'></i></button>
                                                                </div>
                                                            </div><!---end row-->

                                                            <script>
                                                                document.addEventListener('input', function() {
                                                                    const fields = [
                                                                        document.getElementById('StructureName'),
                                                                        document.getElementById('StructureType'),
                                                                        document.getElementById('StructureAddress'),
                                                                        document.getElementById('StructureCity'),
                                                                        document.getElementById('StructureCountry'),
                                                                        document.getElementById('StructurePhone')
                                                                    ];

                                                                    const allFilled = fields.every(field => field.value.trim() !== '');
                                                                    document.getElementById('nextButton').disabled = !allFilled;
                                                                });
                                                            </script>

                                                        </div>

                                                        <div id="test-l-2" role="tabpanel" class="bs-stepper-pane"
                                                            aria-labelledby="stepper1trigger2">

                                                            <h5 class="mb-1">Détails du compte</h5>
                                                            <p class="mb-4">Entrez les détails de votre compte.</p>

                                                            <div class="row g-3">
                                                               
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="InputUsername" class="form-label">Nom
                                                                        d'utilisateur <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control shadow-sm"
                                                                        id="InputUsername"
                                                                        placeholder="Nom d'utilisateur">
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="InputEmail2"
                                                                        class="form-label">Adresse e-mail <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="email"
                                                                        class="form-control shadow-sm"
                                                                        id="InputEmail2"
                                                                        placeholder="exemple@xyz.com">
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="inputChoosePassword"
                                                                        class="form-label">Votre contact <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group" id="contact">
                                                                        <input type="text"
                                                                            class="form-control border-end-0  shadow-sm"
                                                                            id="inputChoosePassword"
                                                                            placeholder="Votre contact" name="contact"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-lg-6">
                                                                    <label for="InputConfirmPassword"
                                                                        class="form-label">Votre adresse <span
                                                                            class="text-danger">*</span></label>
                                                                    <div class="input-group" id="adresse">
                                                                        <input type="text"
                                                                            class="form-control border-end-0  shadow-sm"
                                                                            id="InputConfirmPassword"
                                                                            placeholder="Votre adresse" name="adresse"
                                                                            required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div class="form-check form-switch">
                                                                        <input class="form-check-input"
                                                                            type="checkbox"
                                                                            id="flexSwitchCheckChecked" required>
                                                                        <label class="form-check-label"
                                                                            for="flexSwitchCheckChecked">J'ai lu et
                                                                            j'accepte les conditions générales</label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between">
                                                                        <button class="btn btn-outline-secondary px-4"
                                                                            onclick="stepper1.previous()"><i
                                                                                class='bx bx-left-arrow-alt me-2'></i>Précédent</button>
                                                                        <button class="btn btn-primary px-4 shadow-sm"
                                                                            id="submitBtn"
                                                                            onclick="stepper1.next()"><i
                                                                                class='bx bx-check-circle ms-2'></i>
                                                                            Valider l'inscription</button>
                                                                        <button type="button" id="btnLoading"
                                                                            class="btn btn-dark fw-bold d-none"
                                                                            disabled>
                                                                            <span
                                                                                class="spinner-border spinner-border-sm me-2"
                                                                                role="status"
                                                                                aria-hidden="true"></span>
                                                                            Inscription en cours...
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div><!---end row-->

                                                </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="text-center ">
                                            <p class="mb-0">Vous avez déjà un compte ? <a
                                                    href="{{ route('login') }}"> Connectez-vous ici </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="login-separater text-center mb-5"> <span>OR SIGN IN WITH</span>
                                        <hr>
                                    </div>
                                    <div class="list-inline contacts-social text-center">
                                        <a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
                                        <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
                                        <a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
                                        <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
                                    </div> --}}

                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <!--end row-->
        </div>
    </div>
    </div>
    <!--end wrapper-->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Sélection des éléments du DOM
    const uploader = document.getElementById('image-uploader');
    const fileInput = document.getElementById('logo-upload');
    const previewContainer = document.querySelector('.image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const uploaderContent = document.querySelector('.uploader-content');
    const deleteBtn = document.getElementById('delete-image-btn');

    // Fonction pour afficher l'aperçu de l'image
    const showPreview = (file) => {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            uploaderContent.style.display = 'none'; // Cache la zone d'upload
            previewContainer.style.display = 'block'; // Affiche l'aperçu
        };
        reader.readAsDataURL(file);
    };

    // Fonction pour réinitialiser l'uploader
    const resetUploader = () => {
        fileInput.value = ''; // Important pour pouvoir re-sélectionner le même fichier
        previewImage.src = '#';
        uploaderContent.style.display = 'flex'; // Réaffiche la zone d'upload
        previewContainer.style.display = 'none'; // Cache l'aperçu
    };

    // Événement lorsqu'un fichier est sélectionné
    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            showPreview(file);
        }
    });
    
    // Gérer le glisser-déposer (Drag & Drop) - Bonus !
    uploader.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploader.style.borderColor = 'var(--color-primary)';
    });
    
    uploader.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploader.style.borderColor = 'var(--color-border)';
    });
    
    uploader.addEventListener('drop', (e) => {
        e.preventDefault();
        uploader.style.borderColor = 'var(--color-border)';
        const file = e.dataTransfer.files[0];
        if (file) {
            fileInput.files = e.dataTransfer.files; // Assigne le fichier à l'input
            showPreview(file);
        }
    });

    // Événement pour le bouton de suppression
    deleteBtn.addEventListener('click', () => {
        resetUploader();
    });
});
    </script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bs-stepper/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <script>
        function handleSubmit(event) {
            event.preventDefault();

            const form = event.target.closest('form');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            const btnSubmit = document.getElementById('submitBtn');
            const btnLoading = document.getElementById('btnLoading');

            // Masquer le bouton principal, afficher le bouton loading
            btnSubmit.classList.add('d-none');
            btnLoading.classList.remove('d-none');

            // Soumettre après une courte pause
            setTimeout(() => {
                form.submit();
            }, 500);

            return true;
        }
    </script>
    <!--app JS-->
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>

</html>
