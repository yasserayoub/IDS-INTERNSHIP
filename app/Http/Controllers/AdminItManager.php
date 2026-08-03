<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketAssignment;
use App\Models\ActivityLog;
use App\Models\TicketCategory;
use App\Models\TicketHistory;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Services\ActivityLogger;
use App\Services\TicketHistoryLogger;

class AdminItManager extends Controller
{
    // =========================================================
    // ALL TICKETS
    // =========================================================

    public function AllTickets(Request $request)
    {
        $query = Ticket::with([
            'creator',
            'category',
            'priority',
            'status'
        ]);

        $statuses = TicketStatus::all();
        $priorties = TicketPriority::all();
        $categories = TicketCategory::all();


        // Filter by From Date
        if ($request->filled('from_date')) {
            $query->whereDate(
                'CreatedAt',
                '>=',
                $request->from_date
            );
        }


        // Filter by To Date
        if ($request->filled('to_date')) {
            $query->whereDate(
                'CreatedAt',
                '<=',
                $request->to_date
            );
        }


        // Filter by Status
        if ($request->filled('status')) {
            $query->where(
                'StatusId',
                $request->status
            );
        }


        // Filter by Priority
        if ($request->filled('priority')) {
            $query->where(
                'PriorityId',
                $request->priority
            );
        }


        // Filter by Category
        if ($request->filled('category')) {
            $query->where(
                'CategoryId',
                $request->category
            );
        }


        $tickets = $query
            ->orderBy('CreatedAt', 'desc')
            ->get();


        return view(
            'tickets.index',
            compact(
                'tickets',
                'statuses',
                'priorties',
                'categories'
            )
        );
    }


    // =========================================================
    // SHOW ONE TICKET
    // =========================================================

    public function ShowTicket($id)
    {
        $ticket = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo',
            'comments.user.role',
        ])
        ->where('Id', $id)
        ->firstOrFail();


        // Get IT Support users
        $itSupports = User::whereHas('role', function ($query) {

            $query->where('Name', 'IT Support');

        })
        ->withCount([

            'ticketAssignments as active_tickets_count' => function ($query) {

                $query->where('IsCurrent', true);

            }

        ])
        ->orderBy('active_tickets_count')
        ->orderBy('Name')
        ->get();


        return view(
            'tickets.show',
            compact(
                'ticket',
                'itSupports'
            )
        );
    }


    // =========================================================
    // ASSIGN / REASSIGN TICKET
    // =========================================================

    public function AssignTicket(Request $request, $id)
    {
        $request->validate([
            'AssignedToUserId' => 'required|exists:Users,Id',
        ]);


        // -----------------------------------------------------
        // GET CURRENT ASSIGNMENT
        // -----------------------------------------------------

        $currentAssignment = TicketAssignment::where(
            'TicketId',
            $id
        )
        ->where('IsCurrent', true)
        ->with('assignedTo')
        ->first();


        // -----------------------------------------------------
        // GET NEW AGENT
        // -----------------------------------------------------

        $newUser = User::findOrFail(
            $request->AssignedToUserId
        );


        // -----------------------------------------------------
        // DON'T REASSIGN TO SAME AGENT
        // -----------------------------------------------------

        if (
            $currentAssignment &&
            $currentAssignment->AssignedToUserId == $newUser->Id
        ) {

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Ticket is already assigned to this agent.'
                );
        }


        // -----------------------------------------------------
        // SAVE OLD AGENT NAME FOR HISTORY
        // -----------------------------------------------------

        $oldUserName = $currentAssignment
            ? $currentAssignment->assignedTo->Name
            : 'Not Assigned';


        // -----------------------------------------------------
        // UPDATE ASSIGNMENT
        // -----------------------------------------------------

        DB::transaction(function () use (
            $request,
            $id
        ) {

            // Mark old assignment as inactive
            TicketAssignment::where(
                'TicketId',
                $id
            )
            ->where('IsCurrent', true)
            ->update([

                'IsCurrent' => false,

                'UnassignedAt' => now(),

            ]);


            // Create new assignment
            TicketAssignment::create([

                'TicketId' =>
                    $id,

                'AssignedToUserId' =>
                    $request->AssignedToUserId,

                'AssignedByUserId' =>
                    Auth::id(),

                'AssignedAt' =>
                    now(),

                'IsCurrent' =>
                    true,

            ]);

        });


        // -----------------------------------------------------
        // SAVE TICKET HISTORY
        // -----------------------------------------------------

        TicketHistoryLogger::log(
            $id,
            'Assigned Agent',
            $oldUserName,
            $newUser->Name
        );


        // -----------------------------------------------------
        // SAVE ACTIVITY LOG
        // -----------------------------------------------------

        if ($currentAssignment) {

            // Reassignment
            ActivityLogger::logReassignment(
                $id,
                $oldUserName,
                $newUser->Name
            );

        } else {

            // First assignment
            ActivityLogger::logAssignment(
                $id,
                $newUser->Name
            );

        }


        return redirect()
            ->back()
            ->with(
                'success',
                'Ticket assigned successfully.'
            );
    }


    // =========================================================
    // ACTIVITY HISTORY
    // =========================================================

    public function ActivityHistory($id)
    {
        $ticket = Ticket::findOrFail($id);
    }


    // =========================================================
    // ALL ACTIVITY LOGS
    // =========================================================

    public function ActivityLogs()
    {
        $activityLogs = ActivityLog::with('user')
            ->latest('CreatedAt')
            ->paginate(20);


        return view(
            'tickets.activity',
            compact('activityLogs')
        );
    }


    // =========================================================
    // ALL TICKETS FOR TICKET HISTORY PAGE
    // =========================================================

    public function TicketHistories()
    {
        $tickets = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo'
        ])
        ->orderBy('CreatedAt', 'desc')
        ->paginate(20);


        return view(
            'tickets.histories',
            compact('tickets')
        );
    }


    // =========================================================
    // SHOW HISTORY FOR ONE TICKET
    // =========================================================

    public function ShowTicketHistory($id)
    {
        // Get selected ticket
        $ticket = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo'
        ])
        ->where('Id', $id)
        ->firstOrFail();


        // Get all history records for this ticket
        $histories = TicketHistory::with('changedBy')
            ->where('TicketId', $id)
            ->orderBy('ChangedAt', 'asc')
            ->get();


        return view(
            'tickets.ticket-history',
            compact(
                'ticket',
                'histories'
            )
        );
    }
}
