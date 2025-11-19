<?php
namespace App\Http\Controllers\Auth;
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Societe;
use App\Models\SocieteUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{

    public function authenticate($id)
    {

        // $response = Http::withHeaders([
        //     'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
        //     'accept' => 'application/json',
        // ])->get('https://nedcore.net/api/users/051a1a80-6e6e-4b99-abff-308faf6781e4');

        $response = Http::withHeaders([
            'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            'accept' => 'application/json',
        ])->get('https://nedcore.net/api/users/cac85d41-2764-4784-9ad7-882b2fbf00fa' . $id);

        if ($response->successful()) {
            $data = $response->json();
            $entreprise = $data['entreprise'];
            $userData = $data['user'];

            $logoPath = null;

            if (!empty($entreprise['logo'])) {
                $responseLogo = Http::get($entreprise['logo']);

                if ($responseLogo->successful()) {
                    $extension = pathinfo(parse_url($entreprise['logo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                    $fileName = 'logos/' . uniqid() . '.' . ($extension ?: 'jpg');

                    Storage::disk('public')->put($fileName, $responseLogo->body());
                    $logoPath = $fileName;
                }
            }
            $photoPath = null;

            if (!empty($userData['photo'])) {
                $responsePhoto = Http::get($userData['photo']);

                if ($responsePhoto->successful()) {
                    $extension = pathinfo(parse_url($userData['photo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                    $fileName = 'photos/' . uniqid() . '.' . ($extension ?: 'jpg');

                    Storage::disk('public')->put($fileName, $responsePhoto->body());
                    $photoPath = $fileName;
                }
            }
            // ✅ 1️⃣ Mettre à jour ou créer la société
            $societe = Societe::updateOrCreate(
                ['code_entreprise' => $entreprise['code_societe']],
                [
                    'nom' => $entreprise['nom_societe'],
                    'logo' => $logoPath,
                    'code' => $entreprise['code_societe'],
                ]
            );

            // ✅ 2️⃣ Mettre à jour ou créer l'utilisateur et réécraser le mot de passe à chaque fois
            $user = User::updateOrCreate(
                ['nedcore_user_id' => $userData['id']],
                [
                    'nom' => $userData['name'],
                    'societe_id' => $societe->id,
                    'code_entreprise' => $entreprise['code_societe'],
                    'photo' => $photoPath,
                    'type' => 'Employé',
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'id' => $userData['identifiant'],
                    'password' => $userData['password'], // ✅ Réécraser le mot de passe à chaque connexion
                ]
            );

            // Associer via la table pivot avec les données
            $data = [
                'user_id' => $user->id,
                'entreprise_id' => $societe->id,
            ];

            $updateData = [
                'type' => $request->role ?? 'Employé',
                'est_actif' => true,
                'associe_le' => now(),
            ];

            // Vérifie si une ligne existe déjà, et met à jour ou crée
            SocieteUser::updateOrCreate($data, array_merge($updateData, [
                'id' => Str::uuid(), // Ne sera utilisé que si create()
            ]));

            
            session()->put('entreprise_nom', $entreprise->nom_societe);
            session()->put('entreprise_logo', $entreprise->logo);
            session()->put('entreprise_id', $entreprise->id);
            // ✅ 3️⃣ Authentifier l'utilisateur
            Auth::login($user);

            return redirect()->route('dashboard');
        } else {
            return response()->json(['error' => 'Erreur lors de la récupération des données'], 500);
        }
    }

    /**
     * Affiche la page de connexion.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Traite la connexion de l'utilisateur.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            $request->authenticate(); // Vérifie les identifiants

            $request->session()->regenerate();

            $user = Auth::user();
            $userType = $user->type;

            // Redirection selon le type d'utilisateur
            if ($userType == 0) {
                return redirect()->intended(route('dashboardAdmin'));
            } elseif ($userType == 1) {
                return redirect()->intended(route('dashboardEmployer'));
            } else {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Type d’utilisateur inconnu.');
            }
            
        } catch (\Exception $e) {
            // Gestion des erreurs d'authentification
            return redirect()->route('login')
                             ->with('error', 'Erreur lors de la connexion : ' . $e->getMessage());
        }
    }

    /**
     * Déconnexion de l'utilisateur.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
