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
                                    <div class="form_container"  style="background: rgba(50, 67, 64, 0.799)">
                                        <form class="app-form needs-validation" action="{{ route('register') }}" method="POST" id="otpForm" novalidate
                                            onsubmit="return validateForm(event)">
                                            @csrf
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="mb-4 text-center">
                                                        <h3 class="text-white">Réinitialiser votre mot de passe</h3>
                                                        <p class="text-white-800">Créez un nouveau mot de passe et connectez-vous.</p>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    @if ($errors->any())
                                                        <div
                                                            class="alert alert-light-border-danger d-flex align-items-center justify-content-between">
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
                                                                {{ session('message') ?? session('success') }}
                                                            </p>
                                                            <i class="ti ti-x" data-bs-dismiss="alert"></i>
                                                        </div>
                                                    @endif
                                                    <input type="hidden" name="email" value="{{Session::get('user_contact')}}">
                                                    <div class="mb-3">
                                                        <label class="form-label text-white-800" for="password">Votre nom complet</label>
                                                        <div class="input-group input-group-lg">
                                                            <input type="text" id="nom" name="name"
                                                                class="form-control shadowInput shadow"
                                                                placeholder="Votre nom" required style="font-size: 17px">
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis.
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label text-white-800" for="password">Nouveau mot de
                                                            passe</label>
                                                        <div class="input-group input-group-lg">
                                                            <input type="password" id="password" name="password"
                                                                class="form-control shadowInput shadow"
                                                                placeholder="Mot de passe" required style="font-size: 17px">
                                                            <span class="input-group-text bg-white shadow"
                                                                style="cursor:pointer" onclick="togglePassword1()">
                                                                <i id="togglePwd1" class="fa-solid fa-eye fa-xl"
                                                                    style="font-size: 20px"></i>
                                                            </span>
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis.
                                                            </div>
                                                        </div>
                                                    </div>
    
                                                    <div class="mb-4">
                                                        <label class="form-label text-white-800" for="password_confirmation">Confirmez le mot de
                                                            passe</label>
                                                        <div class="input-group input-group-lg">
                                                            <input type="password" id="password_confirmation"
                                                                name="password_confirmation"
                                                                class="form-control shadowInput shadow"
                                                                placeholder="Mot de passe" required style="font-size: 17px">
                                                            <span class="input-group-text bg-white shadow"
                                                                style="cursor:pointer" onclick="togglePassword2()">
                                                                <i id="togglePwd2" class="fa-solid fa-eye fa-xl"
                                                                    style="font-size: 20px"></i>
                                                            </span>
                                                            <div class="invalid-feedback">
                                                                Ce champ est requis.
                                                            </div>
                                                        </div>
                                                        <small id="passwordError" class="text-danger d-none"
                                                            style="font-size: 15px">Les mots
                                                            de passe ne correspondent pas.</small>
                                                    </div>
    
                                                </div>
                                                <div class="col-12">
                                                    <div class="mb-3">
                                                        <button id="submitBtn" class="btn w-100 mt-3 text-white"
                                                            onclick="handleSubmit(event)" style="background: #0f626a">Enregistrer</button>
                                                        <button type="button" id="btnLoading"
                                                            class="btn btn-dark w-100 fw-bold d-none" disabled>
                                                            <span class="spinner-border spinner-border-sm me-2"
                                                                role="status" aria-hidden="true"></span>
                                                            Traitement en cours...
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
    <script>
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmepassword');
        const submitBtn = document.getElementById('submitBtn');
        const errorText = document.getElementById('passwordError');

        function validatePasswords() {
            const pass = password.value;
            const confirm = confirmPassword.value;

            // Attendre que l'utilisateur ait écrit le même nombre de caractères
            if (pass.length === confirm.length) {
                if (pass === confirm) {
                    submitBtn.disabled = false;
                    errorText.classList.add('d-none');
                } else {
                    submitBtn.disabled = true;
                    errorText.classList.remove('d-none');
                }
            } else {
                // Ne rien faire si les longueurs sont différentes
                submitBtn.disabled = true;
                errorText.classList.add('d-none');
            }
        }

        password.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);
    </script>

    <script>
        function togglePassword1() {
            const input = document.getElementById("password");
            const icon = document.getElementById("togglePwd1");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }

        function togglePassword2() {
            const input = document.getElementById("confirmepassword");
            const icon = document.getElementById("togglePwd2");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        }
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
