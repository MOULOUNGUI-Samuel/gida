<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupportStatisticController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;
        
        // Statistiques générales
        $totalTickets = Ticket::where('assigned_company_id', $company->id)->count();
        $avgResolutionTime = Ticket::where('assigned_company_id', $company->id)
                                ->where('status', 'closed')
                                ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, updated_at)) as avg_time')
                                ->first()->avg_time;
        
        // Répartition par statut
        $statusDistribution = Ticket::where('assigned_company_id', $company->id)
                                ->select('status', DB::raw('COUNT(*) as count'))
                                ->groupBy('status')
                                ->get();
        
        // Répartition par priorité
        $priorityDistribution = Ticket::where('assigned_company_id', $company->id)
                                ->select('priority', DB::raw('COUNT(*) as count'))
                                ->groupBy('priority')
                                ->get();
        
        // Tickets par mois (6 derniers mois)
        $monthlyTickets = Ticket::where('assigned_company_id', $company->id)
                            ->where('created_at', '>=', now()->subMonths(6))
                            ->select(
                                DB::raw('YEAR(created_at) as year'),
                                DB::raw('MONTH(created_at) as month'),
                                DB::raw('COUNT(*) as count')
                            )
                            ->groupBy('year', 'month')
                            ->orderBy('year', 'desc')
                            ->orderBy('month', 'desc')
                            ->get();
        
        return view('support.statistics', compact(
            'totalTickets',
            'avgResolutionTime',
            'statusDistribution',
            'priorityDistribution',
            'monthlyTickets'
        ));
    }
}