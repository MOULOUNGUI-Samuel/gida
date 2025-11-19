<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->company;
        
        $status = $request->get('status', 'all');
        $priority = $request->get('priority', 'all');
        
        $tickets = Ticket::with('user', 'user.company')
                        ->where('assigned_company_id', $company->id);
        
        // Filtrage par statut
        if ($status !== 'all') {
            $tickets->where('status', $status);
        }
        
        // Filtrage par priorité
        if ($priority !== 'all') {
            $tickets->where('priority', $priority);
        }
        
        $tickets = $tickets->orderBy('created_at', 'desc')
                          ->paginate(10);
        
        return view('support.tickets.index', compact('tickets', 'status', 'priority'));
    }
    
    public function show(Ticket $ticket)
    {
        // Vérifier que le ticket est bien assigné à la société de l'utilisateur
        if ($ticket->assigned_company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé à ce ticket.');
        }
        
        $ticket->load('user', 'user.company', 'messages.user');
        
        return view('support.tickets.show', compact('ticket'));
    }
    
    public function update(Request $request, Ticket $ticket)
    {
        // Vérifier que le ticket est bien assigné à la société de l'utilisateur
        if ($ticket->assigned_company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé à ce ticket.');
        }
        
        $validated = $request->validate([
            'estimated_time' => 'nullable|integer|min:1',
            'assigned_to' => 'nullable|exists:users,id'
        ]);
        
        $ticket->update($validated);
        
        return redirect()->back()->with('success', 'Ticket mis à jour avec succès.');
    }
    
    public function addMessage(Request $request, Ticket $ticket)
    {
        // Vérifier que le ticket est bien assigné à la société de l'utilisateur
        if ($ticket->assigned_company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé à ce ticket.');
        }
        
        $validated = $request->validate([
            'message' => 'required|string|min:5'
        ]);
        
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $validated['message']
        ]);
        
        // Mettre à jour le statut du ticket si c'est la première réponse
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }
        
        return redirect()->back()->with('success', 'Message ajouté avec succès.');
    }
    
    public function changeStatus(Request $request, Ticket $ticket)
    {
        // Vérifier que le ticket est bien assigné à la société de l'utilisateur
        if ($ticket->assigned_company_id !== Auth::user()->company_id) {
            abort(403, 'Accès non autorisé à ce ticket.');
        }
        
        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,on_hold,resolved,closed'
        ]);
        
        $ticket->update(['status' => $validated['status']]);
        
        return redirect()->back()->with('success', 'Statut du ticket mis à jour.');
    }
}