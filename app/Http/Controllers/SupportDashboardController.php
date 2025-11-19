<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté.');
        }
        
        $company = $user->company;
        
        if (!$company) {
            return redirect()->route('login')->with('error', 'Vous n\'êtes associé à aucune entreprise.');
        }
        
        // Statistiques des tickets
        $totalTickets = Ticket::where('societe_assignee_id', $company->id)->count();
        $openTickets = Ticket::where('societe_assignee_id', $company->id)
                             ->whereIn('status', ['open', 'in_progress'])
                             ->count();
        $urgentTickets = Ticket::where('societe_assignee_id', $company->id)
                               ->where('priorite', 'urgent')
                               ->whereIn('status', ['open', 'in_progress'])
                               ->count();
        $closedTickets = Ticket::where('societe_assignee_id', $company->id)
                               ->where('status', 'closed')
                               ->count();

        // Tickets récents (les 5 derniers)
        $recentTickets = Ticket::with('user', 'user.company')
                               ->where('societe_assignee_id', $company->id)
                               ->orderBy('created_at', 'desc')
                               ->take(5)
                               ->get();

        return view('support.dashboard', compact(
            'totalTickets',
            'openTickets',
            'urgentTickets',
            'closedTickets',
            'recentTickets'
        ));
    }
}
