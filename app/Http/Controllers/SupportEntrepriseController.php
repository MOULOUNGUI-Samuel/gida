<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Demandes;
use App\Models\Notifications;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SupportEntrepriseController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $pending = collect();
        if ($user->entreprise_id) {
            $pending = Demandes::with('user')
                ->where('entreprise_id', $user->entreprise_id)
                ->whereNotIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $companyName = $user->entreprise->nom ?? $user->societe ?? null;
            if ($companyName) {
                $pending = Demandes::with('user')
                    ->where('societe_assignee', $companyName)
                    ->whereNotIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('supportEntreprise.dashboard', compact('pending'));
    }

    public function historique()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $treated = collect();
        if ($user->entreprise_id) {
            $treated = Demandes::with('user')
                ->where('entreprise_id', $user->entreprise_id)
                ->where(function ($q) {
                    $q->whereIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                      ->orWhere('statut', 'à risque');
                })
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            $companyName = $user->entreprise->nom ?? $user->societe ?? null;
            if ($companyName) {
                $treated = Demandes::with('user')
                    ->where('societe_assignee', $companyName)
                    ->where(function ($q) {
                        $q->whereIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                          ->orWhere('statut', 'à risque');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
            }
        }

        return view('supportEntreprise.historique', compact('treated'));
    }

    public function profil()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $user->load('entreprise');

        $stats = [
            'total' => Demandes::where('entreprise_id', $user->entreprise_id)->count(),
            'resolues' => Demandes::where('entreprise_id', $user->entreprise_id)
                ->whereIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                ->count(),
            'en_cours' => Demandes::where('entreprise_id', $user->entreprise_id)
                ->whereNotIn('workflow_status', ['resolue', 'validee', 'cloturee'])
                ->count()
        ];

        return view('supportEntreprise.profil', compact('user', 'stats'));
    }

    public function home()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $baseQuery = Demandes::query();
        if ($user->entreprise_id) {
            $baseQuery->where('entreprise_id', $user->entreprise_id);
        } else {
            $companyName = $user->entreprise->nom ?? $user->societe ?? null;
            if ($companyName) {
                $baseQuery->where('societe_assignee', $companyName);
            }
        }

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'en_attente' => (clone $baseQuery)->whereIn('workflow_status', ['nouvelle', 'analysee', 'assignee'])->count(),
            'en_traitement' => (clone $baseQuery)->where('workflow_status', 'en_traitement')->count(),
            'resolues' => (clone $baseQuery)->whereIn('workflow_status', ['resolue', 'validee', 'cloturee'])->count(),
            'a_risque' => (clone $baseQuery)->where('statut', 'à risque')->count(),
        ];

        $recent = (clone $baseQuery)->with('user')->orderBy('created_at', 'desc')->limit(5)->get();

        return view('supportEntreprise.homeEntrepiseSupp', compact('stats', 'recent'));
    }

    public function notifications()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        return view('supportEntreprise.notifications');
    }

    public function editProfil()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $user->load('entreprise');
        
        return view('supportEntreprise.edit-profil', compact('user'));
    }

    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'fonction' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);

        $data = $request->only(['nom', 'email', 'fonction']);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('profil-photos', 'public');
            $data['photo'] = $path;
        }

        $user->update($data);

        return redirect()
            ->route('supportEntreprise.profil')
            ->with('success', 'Profil mis à jour avec succès');
    }
}