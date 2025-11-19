<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string'], // Accepts either email or username
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifiant = $this->input('email');
        $password = $this->input('password');

        // Vérification de l'existence des champs requis
        if (empty($identifiant)) {
            throw ValidationException::withMessages([
                'email' => 'Le champ identifiant est requis. Veuillez fournir votre email, numéro de téléphone ou nom d\'utilisateur.',
            ]);
        }

        if (empty($password)) {
            throw ValidationException::withMessages([
                'password' => 'Le champ mot de passe est requis. Veuillez fournir votre mot de passe.',
            ]);
        }

        // On cherche l'utilisateur en vérifiant dans les 3 colonnes possibles.
        $user = User::where('username', $identifiant)->first();

        // ----------------------------------------------------
        //        NOUVELLE LOGIQUE DE GESTION D'ERREUR
        // ----------------------------------------------------

        // Cas 1 : L'identifiant n'existe pas du tout.
        if (! $user) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifiant' => "Aucun compte n'est associé à cet identifiant ! Veuillez vérifier votre numéro de téléphone, email ou nom d'utilisateur.",
            ]);
        }

        // Cas 2 : L'identifiant existe, mais le mot de passe est incorrect.
        if (! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                // On met l'erreur sur le champ 'password' pour une meilleure UX.
                // L'utilisateur saura que c'est le mot de passe qu'il doit corriger.
                'password' => 'Le mot de passe que vous avez saisi est incorrect.',
            ]);
        }

        // Si tout est correct, on connecte l'utilisateur.
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
