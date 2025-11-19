<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Entreprise;

class AuthenticatedSessionController extends Controller
{
    // Affiche le formulaire de login
    public function create(): View
    {
        return view('auth.login');
    }

        
           // http://127.0.0.1:8000/gida/authenticate/cac85d41-2764-4784-9ad7-882b2fbf00fa
           //  dd($response->json());
           // $response = Http::withHeaders([
           // http://127.0.0.1:8000/gida/authenticate/cac85d41-2764-4784-9ad7-882b2fbf00fa
            //     'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            //     'accept' => 'application/json',
            // ])->get('https://nedcore.net/api/users/051a1a80-6e6e-4b99-abff-308faf6781e4');
    
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'code_entreprise' => 'required|string',
            'password' => 'required|string',
        ]);
    
        // Cherche l'utilisateur avec username et code_entreprise
        $user = User::where('username', $request->username)
                    ->where('code_entreprise', $request->code_entreprise)
                    ->first();
              
    
        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors([
                'login_error' => 'Nom d\'utilisateur, code entreprise ou mot de passe incorrect.'
            ])->withInput();
        }
    
        // Authentifie l'utilisateur
        Auth::login($user);

        // Regenerate session to prevent fixation
        try {
            $request->session()->regenerate();
        } catch (\Exception $e) {
            // ignore session regeneration errors in CLI/tests
        }

        // If email not verified, mark as verified so 'verified' middleware won't block internal users
        if (empty($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
        }

        // Redirection selon le type d'utilisateur
        if ($user->type == 0) {
            return redirect()->route('dashboard');
        } elseif ($user->type == 2) {
            return redirect()->route('supportEntreprise.dashboard');
        } else {
            return redirect()->route('dashboardEmployer');
        }
    }

    // Authentification via l'API Nedcore
    public function authenticate($id): RedirectResponse
    {
        $response = Http::withHeaders([
            'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            'accept' => 'application/json',
        ])->get('https://nedcore.net/api/users/' . $id);
   

        if (!$response->successful()) {
            return redirect()->back()->withErrors(['api_error' => 'Erreur lors de la récupération des données']);
        }

        $data = $response->json();
        $entrepriseData = $data['entreprise'];
        $userData = $data['user'];

        // Gestion du logo de l’entreprise
        $logoPath = null;
        if (!empty($entrepriseData['logo'])) {
            $responseLogo = Http::get($entrepriseData['logo']);
            if ($responseLogo->successful()) {
                $extension = pathinfo(parse_url($entrepriseData['logo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                $logoPath = 'logos/' . uniqid() . '.' . ($extension ?: 'jpg');
                Storage::disk('public')->put($logoPath, $responseLogo->body());
            }
        }

        // Création ou mise à jour de l’entreprise
        $entreprise = Entreprise::updateOrCreate(
            ['code' => $entrepriseData['code_societe']],
            [
                'nom' => $entrepriseData['nom_societe'],
                'logo' => $logoPath,
                'email' => $entrepriseData['email'] ?? null,
                'telephone' => $entrepriseData['telephone'] ?? null,
                'statut' => $entrepriseData['statut'] ?? null,
                'adresse' => $entrepriseData['adresse'] ?? null,
            ]
        );

        // Gestion de la photo de l’utilisateur
        $photoPath = null;
        if (!empty($userData['photo'])) {
            $responsePhoto = Http::get($userData['photo']);
            if ($responsePhoto->successful()) {
                $extension = pathinfo(parse_url($userData['photo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                $photoPath = 'photos/' . uniqid() . '.' . ($extension ?: 'jpg');
                Storage::disk('public')->put($photoPath, $responsePhoto->body());
            }
        }

        // Création ou mise à jour de l'utilisateur
        $user = User::updateOrCreate(
            ['nedcore_user_id' => $userData['id']],
            [
                'nom' => $userData['name'],
                'entreprise_id' => $entreprise->id,
                'code_entreprise' => $entreprise->code,
                'photo' => $photoPath,
                'type' => 1, // Employé
                'username' => $userData['username'],
                'email' => $userData['email'],
                'matricule' => $userData['identifiant'],
                'password' => Hash::make($userData['password']),
                'fonction' => 'Employe',
                'societe' => $entreprise->nom,
                'nedcore_user_id' =>  $userData['id'],
            ]
        );

        // Stockage des infos de l’entreprise en session
        session([
            'entreprise_nom' => $entreprise->nom,
            'entreprise_logo' => $entreprise->logo,
            'entreprise_id' => $entreprise->id
        ]);

        // Connexion automatique de l'utilisateur
        Auth::login($user);

        return redirect()->route('dashboardEmployer');
    }

    // Déconnexion
    public function destroy(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
