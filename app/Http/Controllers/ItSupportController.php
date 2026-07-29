<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketComment;
use App\Services\ActivityLogger;

class ItSupportController extends Controller
{
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

    if ($request->filled('search')) {

        $query->where(function ($q) use ($request) {

            $q->where('Title', 'ILIKE', '%' . $request->search . '%')
              ->orWhere('ReferenceNumber', 'ILIKE', '%' . $request->search . '%');

        });

    }

    if ($request->filled('status')) {
        $query->where('StatusId', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('PriorityId', $request->priority);
    }

    $tickets = $query
        ->latest('CreatedAt')
        ->get();

    return view('ItAgent.MyAssignedTickets', [

        'tickets' => $tickets,

        'assignedCount' => $tickets->count(),

        'openCount' => $tickets->where('status.Name', 'Open')->count(),

        'progressCount' => $tickets->where('status.Name', 'In Progress')->count(),

        'pendingCount' => $tickets->where('status.Name', 'Pending')->count(),

        'statuses' => TicketStatus::all(),

        'priorities' => TicketPriority::all(),

    ]);
}


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

    return view('ItAgent.show-ticket', compact('ticket', 'statuses'));
}
public function UpdateTicket(Request $request, $id)
{
    $request->validate([
        'StatusId' => 'required|exists:TicketStatuses,Id',
    ]);

    $ticket = Ticket::findOrFail($id);

    // Get the old status name
    $oldStatus = $ticket->status->Name;

    // Update the status
    $ticket->StatusId = $request->StatusId;
    $ticket->save();

    // Reload the new status
    $ticket->load('status');
    $newStatus = $ticket->status->Name;

    // Log the activity
    ActivityLogger::logStatusChange(
    $ticket->Id,
    $oldStatus,
    $newStatus
);
   

    return redirect()
        ->route('support.ticket.show', $ticket->Id)
        ->with('success', 'Ticket status updated successfully.');
}
public function storeComment(Request $request, $id)
{
    $request->validate([
        'Content' => 'required|string|max:5000',
    ]);

    $ticket = Ticket::findOrFail($id);

    // Ensure the logged-in IT Support is assigned to this ticket
    if (
        !$ticket->currentAssignment ||
        $ticket->currentAssignment->AssignedToUserId != Auth::user()->Id
    ) {
        abort(403, 'You are not assigned to this ticket.');
    }

    // Save the comment
    TicketComment::create([
        'TicketId'   => $ticket->Id,
        'UserId'     => Auth::user()->Id,
        'Content'    => $request->Content,
        'IsInternal' => $request->has('IsInternal'),
        'CreatedAt'  => now(),
        'UpdatedAt'  => now(),
    ]);

    // Log the activity
    ActivityLogger::log(
        $request->has('IsInternal') ? 'Internal Note Added' : 'Comment Added',
        'Ticket',
        $ticket->Id,
        Auth::user()->Name .
        ($request->has('IsInternal')
            ? ' added an internal note.'
            : ' added a comment.')
    );

    return redirect()
        ->route('support.ticket.show', $ticket->Id)
        ->with('success', 'Comment added successfully.');
}


}
