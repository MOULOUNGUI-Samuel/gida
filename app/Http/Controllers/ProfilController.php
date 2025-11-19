<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profil;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // Affiche le profil et le formulaire
    public function show()
    {
        $user = Auth::user();

        // Récupère ou crée le profil associé à l'utilisateur
        $profil = Profil::firstOrCreate(
            ['user_id' => $user->id],
            [
                'societe' => '',
                'preferences_notif' => '',
                'accessibilite' => '',
                'photo' => null
            ]
        );

        return view('profil.show', compact('user', 'profil'));
    }

    // Met à jour le profil
    public function update(Request $request)
    {
        $user = Auth::user();
        $profil = Profil::firstOrCreate(
            ['user_id' => $user->id],
            [
                'societe' => '',
                'preferences_notif' => '',
                'accessibilite' => '',
                'photo' => null
            ]
        );

        // Validation
        $request->validate([
            'nom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'societe' => 'nullable|string|max:255',
            'preferences_notif' => 'nullable|string|max:255',
            'accessibilite' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:2048',
        ]);


        // MAJ profil
        $profil->update([
            'societe' => $request->societe,
            'preferences_notif' => $request->preferences_notif,
            'accessibilite' => $request->accessibilite,
        ]);

        // Upload photo
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profil', 'public');
            $profil->update(['photo' => $path]);
        }

        return redirect()->route('profil.show')->with('success', 'Profil mis à jour avec succès.');
    }
}
