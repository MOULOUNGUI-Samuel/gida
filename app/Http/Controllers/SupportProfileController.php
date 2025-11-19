<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupportProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('support.profile', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'notification_preferences' => 'nullable|array'
        ]);
        
        // Mise à jour des informations de base
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? $user->phone,
            'notification_preferences' => $validated['notification_preferences'] ?? $user->notification_preferences
        ]);
        
        // Mise à jour du mot de passe si fourni
        if (!empty($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }
            
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);
        }
        
        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }
}