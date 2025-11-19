<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    { // 1. Validation de base des champs
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    
        $email = $request->input('email');
        $loginField = ''; // On va déterminer le type de champ
    
        // 2. Détection du type d'email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
            // Validation spécifique pour l'email
            $request->validate(['email' => ['unique:users,email']]);
        } elseif (preg_match('/^[0-9\+\-\(\) ]+$/', $email)) {
            $loginField = 'phone_number';
            // Validation spécifique pour le numéro de téléphone
            $request->validate(['email' => ['unique:users,phone_number']]);
        } else {
            $loginField = 'username';
            // Validation spécifique pour le nom d'utilisateur
            $request->validate(['email' => ['unique:users,username']]);
        }
    
        // 3. Création de l'utilisateur avec le bon champ
        $user = User::create([
            'name' => $request->name,
            $loginField => $email, // <-- Magie ici ! On utilise le champ détecté.
            'password' => Hash::make($request->password),
        ]);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect(route('dashboard', absolute: false));
    }
}
