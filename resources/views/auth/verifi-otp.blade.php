<!DOCTYPE html>
<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
<meta content="IE=edge" http-equiv="X-UA-Compatible">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<meta content="Ingenium Santé est une plateforme digitale innovante" name="description">
<meta
    content="Ingenium Santé est une plateforme digitale innovante qui connecte les assurés, les entreprises clientes, les assureurs et les prestataires de santé. Elle permet une gestion dématérialisée et instantanée des informations relatives aux couvertures santé."
    name="keywords">

<link rel="icon" href="{{ asset('assets/images/logo/iconlogo1.png') }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset('assets/images/logo/iconlogo1.png') }}" type="image/x-icon">

<title>Connexion</title>

<!-- Animation css -->
<link rel="stylesheet" href="{{ asset('assets/vendor/animation/animate.min.css') }}">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/" rel="preconnect">
<link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
<link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&amp;display=swap"
    rel="stylesheet">

<!--Flag Icon css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}">

<!-- Tabler icons-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/tabler-icons/tabler-icons.css') }}">

<!-- Prism css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/prism/prism.min.css') }}">

<!-- Bootstrap css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/bootstrap/bootstrap.min.css') }}">

<!-- Simplebar css-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/simplebar/simplebar.css') }}">
<!--font-awesome-css-->
<link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome/css/all.css') }}">

<link rel="preload" as="style" href="{{ asset('build/assets/style-Cuxwy5N_.css') }}" />
<link rel="stylesheet" href="{{ asset('build/assets/style-Cuxwy5N_.css') }}" /><!-- phosphor-icon css-->
<link href="{{ asset('assets/vendor/phosphor/phosphor-bold.css') }}" rel="stylesheet">
<style>

</style>

<body class="sign-in-bg">
    <div class="app-wrapper d-block">
        <div class="main-container">
            <!-- sign up start -->
            <div class="container main-container">
                <main class="w-100 p-0">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- Vérification par OTP 1 start -->
                            <div class="col-12 p-0 ">
                                <div class="login-form-container">
                                    <div class="mb-4">
                                        <a class="logo" href="index.html">
                                            <img alt="#" src="{{ asset('assets/images/logo/iconlogo1.png') }}"
                                                width="55">
                                        </a>
                                    </div>
                                    <div class="form_container" style="background: rgba(50, 67, 64, 0.799)">
                                        <form class="app-form" action="{{ route('verificationOTP.check') }}" method="POST" id="otpForm">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-5 text-center">
                                                        <h2 class="text-white-800">Vérification par OTP</h2>
                                                        <p class="text-white-700">Adresse :
                                                            {{ Session::get('verification_contact') }}</p>
                                                        <p class="text-white">Saisissez le code à 6 chiffres envoyé par
                                                            SMS ou par Email</p>
                                                        <div id="timer" class="mt-2 fw-bold fs-5 text-white">
                                                        </div>
                                                        <div id="resend-message" class="mt-2 text-success"
                                                            style="display: none;"></div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    @if ($errors->has('otp_code'))
                                                        <div
                                                            class="alert alert-light-border-danger d-flex align-items-center justify-content-between">
                                                            <p class="mb-0 text-danger">
                                                                <i class="ti ti-alert-circle f-s-18 me-2"></i>
                                                                {{ $errors->first('otp_code') }}
                                                            </p>
                                                            <i class="ti ti-x" data-bs-dismiss="alert"></i>
                                                        </div>
                                                    @endif
                                                    @if (session('success'))
                                                        <div
                                                            class="alert alert-light-border-success d-flex align-items-center justify-content-between">
                                                            <p class="mb-0 text-success">
                                                                <i class="ti ti-circle-check f-s-18 me-2"></i>
                                                                {{ session('message') ?? session('success') }}
                                                            </p>
                                                            <i class="ti ti-x" data-bs-dismiss="alert"></i>
                                                        </div>
                                                    @endif
                                                    <div class="verification-box">
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="one" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(1)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="two" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(2)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="three" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(3)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="four" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(4)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="five" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(5)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                        <div>
                                                            <input class="otp-input form-control h-50 w-50 text-center"
                                                                id="six" maxlength="1" name="otp_digits[]"
                                                                required onkeyup='tabChange(6)' type="text"
                                                                style="font-size: 20px;">
                                                        </div>
                                                    </div>
                                                    <!-- Champ caché qui contiendra le code complet -->
                                                    <input type="hidden" name="otp_code" id="otp_code">

                                                </div>
                                                <div class="col-12">
                                                    <p class="text-white-700">Je n'ai pas reçu de code ?
                                                        <a class="link-primary text-info"
                                                            href="#" id="resend-link">Renvoyer</a>
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <button type="submit" id="verify-button"
                                                            class="btn w-100 text-white"
                                                            onclick="handleSubmit(event)" style="background: #0f7983">Vérifier</button>
                                                        <button type="button" id="btnLoading"
                                                            class="btn btn-dark w-100 fw-bold d-none" disabled>
                                                            <span class="spinner-border spinner-border-sm me-2"
                                                                role="status" aria-hidden="true"></span>
                                                            Vérification en cours...
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('login') }}" class="btn btn-link text-danger px-0">
                                                <i class="ti ti-arrow-left me-1"></i>Annuler la vérification
                                            </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- Verify OTP 1 end -->
                        </div>
                    </div>
                </main>
            </div>
            <!-- sign up end -->
        </div>
    </div>
    <script>
        function handleSubmit(event) {
            event.preventDefault();

            const form = event.target.closest('form');
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }

            const btnSubmit = document.getElementById('verify-button');
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélection des éléments du DOM
            const otpInputs = document.querySelectorAll('.otp-input');
            const otpForm = document.getElementById('otpForm');
            const hiddenOtpInput = document.getElementById('otp_code');
            const timerElement = document.getElementById('timer');
            const resendLink = document.getElementById('resend-link');
            const verifyButton = document.getElementById('verify-button');
            const resendMessage = document.getElementById('resend-message');

            let timerInterval;

            // --- Fonction pour démarrer et gérer le minuteur ---
            function startTimer(duration) {
                let timer = duration;

                // (Ré)activer le bouton de vérification et cacher le message de renvoi
                verifyButton.disabled = false;
                resendMessage.style.display = 'none';

                // Désactiver le lien de renvoi pendant que le minuteur tourne
                resendLink.style.pointerEvents = 'none';
                resendLink.classList.add('text-muted');

                clearInterval(timerInterval); // Nettoyer tout minuteur précédent

                timerInterval = setInterval(function() {
                    const minutes = String(Math.floor(timer / 60)).padStart(2, '0');
                    const seconds = String(timer % 60).padStart(2, '0');

                    timerElement.textContent = `Le code expire dans : ${minutes}:${seconds}`;

                    if (--timer < 0) {
                        clearInterval(timerInterval);
                        timerElement.textContent = "Le code a expiré.";
                        timerElement.classList.add('text-danger');
                        verifyButton.disabled = true; // Désactiver la vérification
                        resendLink.style.pointerEvents = 'auto'; // Activer le lien de renvoi
                        resendLink.classList.remove('text-muted');
                    }
                }, 1000);
            }

            // --- Logique pour la saisie dans les champs OTP ---
            otpInputs.forEach((input, index) => {
                // Gérer la saisie
                input.addEventListener('input', () => {
                    input.value = input.value.replace(/[^0-9]/g,
                        ''); // N'autoriser que les chiffres
                    if (input.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus(); // Passer au champ suivant
                    }
                });

                // Gérer la touche "Retour Arrière"
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && input.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus(); // Revenir au champ précédent
                    }
                });

                // Gérer le collage d'un code
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const pasteData = (e.clipboardData || window.clipboardData).getData('text')
                        .trim();
                    if (/^\d{6}$/.test(pasteData)) {
                        pasteData.split('').forEach((char, i) => {
                            if (otpInputs[i]) {
                                otpInputs[i].value = char;
                            }
                        });
                        otpInputs[5].focus();
                    }
                });
            });

            // --- Action avant la soumission du formulaire ---
            otpForm.addEventListener('submit', function(e) {
                // Concaténer les chiffres des 6 champs dans le champ caché
                hiddenOtpInput.value = Array.from(otpInputs).map(input => input.value).join('');

                // Si le code n'est pas complet, on arrête la soumission
                if (hiddenOtpInput.value.length !== 6) {
                    e.preventDefault();
                    alert('Veuillez saisir les 6 chiffres du code.');
                }
            });

            // --- Logique pour renvoyer le code via AJAX ---
            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                resendLink.textContent = 'Envoi en cours...';
                resendLink.style.pointerEvents = 'none';

                fetch("{{ route('verificationOTP.resend') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        resendLink.textContent = 'Renvoyer';
                        if (data.success) {
                            resendMessage.textContent = data.message;
                            resendMessage.style.display = 'block';

                            // Redémarrer le minuteur avec la nouvelle date d'expiration
                            const expiresAt = new Date(data.expires_at);
                            const duration = Math.floor((expiresAt.getTime() - Date.now()) / 1000);
                            startTimer(duration);
                        } else {
                            alert(data.message || 'Une erreur est survenue.');
                            resendLink.style.pointerEvents = 'auto';
                        }
                    })
                    .catch(() => {
                        alert('Erreur de communication avec le serveur.');
                        resendLink.textContent = 'Renvoyer';
                        resendLink.style.pointerEvents = 'auto';
                    });
            });

            // --- DÉMARRAGE INITIAL DU MINUTEUR ---
            const initialExpiresAtString =
                "{{ session('verification_code_expires_at') ? session('verification_code_expires_at')->toIso8601String() : '' }}";
            if (initialExpiresAtString) {
                const initialExpiresAt = new Date(initialExpiresAtString);
                const duration = Math.floor((initialExpiresAt.getTime() - Date.now()) / 1000);

                if (duration > 0) {
                    startTimer(duration);
                } else {
                    // Le code était déjà expiré au chargement de la page
                    timerElement.textContent = "Le code a expiré.";
                    verifyButton.disabled = true;
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.classList.remove('text-muted');
                }
            }
        });
    </script>
    <!--js-->
    <script src="{{ asset('assets/js/coming_soon.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- latest jquery-->
    <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('assets/vendor/simplebar/simplebar.js') }}"></script>

    <!-- phosphor js -->
    <script src="{{ asset('assets/vendor/phosphor/phosphor.js') }}"></script>

    <script src="{{ asset('assets/js/project_app.js') }}"></script>
</body>

<!-- Mirrored from phplaravel-1384472-5380003.cloudwaysapps.com/sign_up by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Jun 2025 02:03:15 GMT -->

</html>
