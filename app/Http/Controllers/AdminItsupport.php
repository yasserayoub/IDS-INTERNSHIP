<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\TicketPriority;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class AdminITsupport extends Controller
{
    public function AllTickets(Request $request)
    {
        // Build the ticket query
        $query = Ticket::with([
            'creator',
            'category',
            'priority',
            'status'
        ]);

        // Retrieve all statuses for the filter dropdown
        $statuses = TicketStatus::all();
        $priorties=TicketPriority::all();
        $categories=TicketCategory::all();

        // Filter by From Date
        if ($request->filled('from_date')) {
            $query->whereDate('CreatedAt', '>=', $request->from_date);
        }

        // Filter by To Date
        if ($request->filled('to_date')) {
            $query->whereDate('CreatedAt', '<=', $request->to_date);
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('StatusId', $request->status);
        }

        // Filter by status
        if ($request->filled('priority')) {
            $query->where('PriorityId', $request->priority);
        }
           // Filter by catgory
        if ($request->filled('category')) {
            $query->where('CategoryId', $request->category);
        }

        // Retrieve the filtered tickets
        $tickets = $query
            ->orderBy('CreatedAt', 'desc')
            ->get();

        // Send tickets and statuses to the view
        return view('tickets.index', compact('tickets', 'statuses','priorties','categories'));
    }
}
