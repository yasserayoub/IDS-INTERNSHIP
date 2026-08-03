<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketComment;

use App\Services\ActivityLogger;
use App\Services\TicketHistoryLogger;

class ItSupportController extends Controller
{

    // =========================================================
    // SHOW ALL TICKETS ASSIGNED TO THE LOGGED-IN IT SUPPORT
    // =========================================================

    public function MyTickets(Request $request)
    {
        $query = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo'
        ])
        ->whereHas('currentAssignment', function ($query) {
            $query->where('AssignedToUserId', Auth::id());
        });


        // Search by title or reference number
        if ($request->filled('search')) {

            $query->where(function ($q) use ($request) {

                $q->where(
                    'Title',
                    'ILIKE',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'ReferenceNumber',
                    'ILIKE',
                    '%' . $request->search . '%'
                );

            });
        }


        // Filter by status
        if ($request->filled('status')) {

            $query->where(
                'StatusId',
                $request->status
            );

        }


        // Filter by priority
        if ($request->filled('priority')) {

            $query->where(
                'PriorityId',
                $request->priority
            );

        }


        $tickets = $query
            ->latest('CreatedAt')
            ->get();


        return view('ItAgent.MyAssignedTickets', [

            'tickets' => $tickets,

            'assignedCount' => $tickets->count(),

            'openCount' => $tickets
                ->where('status.Name', 'Open')
                ->count(),

            'progressCount' => $tickets
                ->where('status.Name', 'In Progress')
                ->count(),

            'pendingCount' => $tickets
                ->where('status.Name', 'Pending')
                ->count(),

            'statuses' => TicketStatus::all(),

            'priorities' => TicketPriority::all(),

        ]);
    }


    // =========================================================
    // VIEW ONE ASSIGNED TICKET
    // =========================================================

    public function ViewTicketDetails($id)
    {
        $ticket = Ticket::with([

            'creator',
            'category',
            'priority',
            'status',

            'currentAssignment.assignedTo',

            'attachments',

            'comments.user.role'

        ])
        ->where('Id', $id)
        ->firstOrFail();


        $statuses = TicketStatus::all();


        return view(
            'ItAgent.show-ticket',
            compact('ticket', 'statuses')
        );
    }


    // =========================================================
    // UPDATE TICKET STATUS
    // =========================================================

    public function UpdateTicket(Request $request, $id)
{
    // Validate selected status
    $request->validate([
        'StatusId' => 'required|exists:TicketStatuses,Id',
    ]);

    // Get the ticket with its current status
    $ticket = Ticket::with('status')
        ->where('Id', $id)
        ->firstOrFail();

    // Get OLD status
    $oldStatus = $ticket->status->Name;

    // Get NEW status directly from the selected StatusId
    $newStatusRecord = TicketStatus::where('Id', $request->StatusId)
        ->firstOrFail();

    $newStatus = $newStatusRecord->Name;

    // Only update if the status actually changed
    if ($oldStatus != $newStatus) {

        // Update ticket status
        $ticket->StatusId = $request->StatusId;
        $ticket->UpdatedAt = now();
        $ticket->save();

        // Record the change in TicketHistories
        TicketHistoryLogger::log(
            $ticket->Id,
            'Status',
            $oldStatus,
            $newStatus
        );

        // Record the change in ActivityLogs
        ActivityLogger::logStatusChange(
            $ticket->Id,
            $oldStatus,
            $newStatus
        );
    }

    return redirect()
        ->route('support.ticket.show', $ticket->Id)
        ->with('success', 'Ticket status updated successfully.');
}


    // =========================================================
    // ADD COMMENT / INTERNAL NOTE
    // =========================================================

    public function storeComment(Request $request, $id)
    {
        // Validate comment
        $request->validate([

            'Content' =>
                'required|string|max:5000',

        ]);


        $ticket = Ticket::findOrFail($id);


        // -----------------------------------------------------
        // CHECK THAT THIS IT SUPPORT IS ASSIGNED TO THE TICKET
        // -----------------------------------------------------

        if (
            !$ticket->currentAssignment ||

            $ticket
                ->currentAssignment
                ->AssignedToUserId != Auth::id()
        ) {

            abort(
                403,
                'You are not assigned to this ticket.'
            );

        }


        // -----------------------------------------------------
        // SAVE COMMENT
        // -----------------------------------------------------

        TicketComment::create([

            'TicketId' =>
                $ticket->Id,

            'UserId' =>
                Auth::id(),

            'Content' =>
                $request->Content,

            'IsInternal' =>
                $request->has('IsInternal'),

            'CreatedAt' =>
                now(),

            'UpdatedAt' =>
                now(),

        ]);


        // -----------------------------------------------------
        // SAVE ACTIVITY LOG
        // -----------------------------------------------------

        ActivityLogger::log(

            $request->has('IsInternal')
                ? 'Internal Note Added'
                : 'Comment Added',

            'Ticket',

            $ticket->Id,

            Auth::user()->Name .

            (
                $request->has('IsInternal')

                    ? ' added an internal note.'

                    : ' added a comment.'
            )

        );


        return redirect()
            ->route(
                'support.ticket.show',
                $ticket->Id
            )
            ->with(
                'success',
                'Comment added successfully.'
            );
    }

}
