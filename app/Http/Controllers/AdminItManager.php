<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketAssignment;
use App\Models\ActivityLog;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class AdminItManager extends Controller
{
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

        if ($request->filled('from_date')) {
            $query->whereDate('CreatedAt', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('CreatedAt', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('StatusId', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('PriorityId', $request->priority);
        }

        if ($request->filled('category')) {
            $query->where('CategoryId', $request->category);
        }

        $tickets = $query
            ->orderBy('CreatedAt', 'desc')
            ->get();

        return view('tickets.index', compact(
            'tickets',
            'statuses',
            'priorties',
            'categories'
        ));
    }

    public function ShowTicket($id)
    {
        $ticket = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo',
            'comments.user.role',
        ])->where('Id', $id)->firstOrFail();

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

        return view('tickets.show', compact('ticket', 'itSupports'));
    }

    public function AssignTicket(Request $request, $id)
    {
        $request->validate([
            'AssignedToUserId' => 'required|exists:Users,Id',
        ]);

        // Get the current assignment before changing it
        $currentAssignment = TicketAssignment::where('TicketId', $id)
            ->where('IsCurrent', true)
            ->with('assignedTo')
            ->first();

        DB::transaction(function () use ($request, $id) {

            // Mark the current assignment (if any) as inactive
            TicketAssignment::where('TicketId', $id)
                ->where('IsCurrent', true)
                ->update([
                    'IsCurrent' => false,
                    'UnassignedAt' => now(),
                ]);

            // Create the new assignment
            TicketAssignment::create([
                'TicketId' => $id,
                'AssignedToUserId' => $request->AssignedToUserId,
                'AssignedByUserId' => Auth::id(),
                'AssignedAt' => now(),
                'IsCurrent' => true,
            ]);
        });

        // Get the newly assigned user
        $newUser = User::findOrFail($request->AssignedToUserId);

        // Log the assignment
        if ($currentAssignment) {
            ActivityLogger::logReassignment(
                $id,
                $currentAssignment->assignedTo->Name,
                $newUser->Name
            );
        } else {
            ActivityLogger::logAssignment(
                $id,
                $newUser->Name
            );
        }

        return redirect()
            ->back()
            ->with('success', 'Ticket assigned successfully.');
    }
    public function ActivityHistory($id)
{
    $ticket = Ticket::findOrFail($id);
}
public function ActivityLogs()
{
    $activityLogs = ActivityLog::with('user')
        ->latest('CreatedAt')
        ->paginate(20);

    return view('tickets.activity', compact('activityLogs'));
}
}
