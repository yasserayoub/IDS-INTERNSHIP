<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;

class ItManagerController extends Controller
{
    // =========================================================
    // IT MANAGER DASHBOARD
    // =========================================================

    public function dashboard()
    {
        // =====================================================
        // KPI CARDS
        // =====================================================

        $totalTickets = Ticket::count();


        $openTickets = Ticket::whereHas('status', function ($query) {
            $query->where('Name', 'Open');
        })->count();


        $pendingTickets = Ticket::whereHas('status', function ($query) {
            $query->where('Name', 'Pending');
        })->count();


        $resolvedTickets = Ticket::whereHas('status', function ($query) {
            $query->where('Name', 'Resolved');
        })->count();


        // =====================================================
        // RECENT TICKETS
        // =====================================================

        $recentTickets = Ticket::with([
            'priority',
            'status',
            'currentAssignment.assignedTo'
        ])
        ->orderBy('CreatedAt', 'desc')
        ->take(5)
        ->get();


        // =====================================================
        // CHART 1
        // TICKET STATUS DISTRIBUTION
        // =====================================================

        $statusData = Ticket::selectRaw(
            '"TicketStatuses"."Name" as status_name, COUNT("Tickets"."Id") as total'
        )
        ->join(
            'TicketStatuses',
            'Tickets.StatusId',
            '=',
            'TicketStatuses.Id'
        )
        ->groupBy('TicketStatuses.Id', 'TicketStatuses.Name')
        ->orderBy('TicketStatuses.Id')
        ->get();


        // Chart.js labels
        $statusLabels = $statusData
            ->pluck('status_name')
            ->values();


        // Chart.js values
        $statusCounts = $statusData
            ->pluck('total')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();


        // =====================================================
        // CHART 2
        // TICKET PRIORITY DISTRIBUTION
        // =====================================================

        $priorityData = Ticket::selectRaw(
            '"TicketPriorities"."Name" as priority_name, COUNT("Tickets"."Id") as total'
        )
        ->join(
            'TicketPriorities',
            'Tickets.PriorityId',
            '=',
            'TicketPriorities.Id'
        )
        ->groupBy('TicketPriorities.Id', 'TicketPriorities.Name')
        ->orderBy('TicketPriorities.Id')
        ->get();


        // Chart.js labels
        $priorityLabels = $priorityData
            ->pluck('priority_name')
            ->values();


        // Chart.js values
        $priorityCounts = $priorityData
            ->pluck('total')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();


        // =====================================================
        // CHART 3
        // IT SUPPORT AGENT WORKLOAD
        // =====================================================

        $agents = User::whereHas('role', function ($query) {

            $query->where('Name', 'IT Support');

        })
        ->withCount([

            'ticketAssignments as active_tickets_count'
                => function ($query) {

                    $query->where('IsCurrent', true);

                }

        ])
        ->orderByDesc('active_tickets_count')
        ->orderBy('Name')
        ->get();


        // Agent names
        $agentLabels = $agents
            ->pluck('Name')
            ->values();


        // Active tickets for each agent
        $agentTicketCounts = $agents
            ->pluck('active_tickets_count')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();


        return view('dashboard.index', compact(

            // KPI cards
            'totalTickets',
            'openTickets',
            'pendingTickets',
            'resolvedTickets',

            // Recent tickets
            'recentTickets',

            // Status chart
            'statusLabels',
            'statusCounts',

            // Priority chart
            'priorityLabels',
            'priorityCounts',

            // Agent workload chart
            'agentLabels',
            'agentTicketCounts'

        ));
    }


    // =========================================================
    // STORE MANAGER COMMENT / INTERNAL NOTE
    // =========================================================

    public function storeComment(Request $request, $id)
    {
        // Validate comment
        $request->validate([
            'Content' => 'required|string|max:5000',
        ]);


        // Make sure ticket exists
      $ticket = Ticket::with('currentAssignment')
    ->where('Id', $id)
    ->firstOrFail();



        // =====================================================
        // CREATE COMMENT
        // =====================================================

        TicketComment::create([

            'TicketId' => $ticket->Id,

            'UserId' => Auth::id(),

            'Content' => $request->Content,

            'IsInternal' =>
                Auth::user()->role->Name !== 'Employee'
                    ? $request->has('IsInternal')
                    : false,

            'CreatedAt' => now(),

            'UpdatedAt' => now(),

        ]);
        // -----------------------------------------------------
// SEND NOTIFICATIONS
// -----------------------------------------------------

$message = Auth::user()->Name .
    ' commented on ticket ' .
    $ticket->ReferenceNumber . '.';

// Notify ticket creator (Employee)
NotificationService::send(
    $ticket->CreatedByUserId,
    $ticket->Id,
    'comment_added',
    'New Comment',
    $message
);

// Notify assigned IT Support
if ($ticket->currentAssignment) {

    NotificationService::send(
        $ticket->currentAssignment->AssignedToUserId,
        $ticket->Id,
        'comment_added',
        'New Comment',
        $message
    );

}


        return redirect()
            ->back()
            ->with(
                'success',
                'Comment added successfully.'
            );
    }
}
