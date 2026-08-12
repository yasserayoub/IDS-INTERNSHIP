<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExport;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    // =========================================================
    // REPORT PAGE
    // =========================================================

    public function index(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // =====================================================
        // BASE TICKET QUERY
        // =====================================================

        $ticketQuery = Ticket::query();

        if ($fromDate) {
            $ticketQuery->whereDate('CreatedAt', '>=', $fromDate);
        }

        if ($toDate) {
            $ticketQuery->whereDate('CreatedAt', '<=', $toDate);
        }

        // =====================================================
        // BASIC TICKET COUNTS
        // =====================================================

        $totalTickets = (clone $ticketQuery)->count();

        $openTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'Open');
            })
            ->count();

        $pendingTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'Pending');
            })
            ->count();

        $inProgressTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'In Progress');
            })
            ->count();

        $resolvedTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'Resolved');
            })
            ->count();

        $closedTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'Closed');
            })
            ->count();

        $cancelledTickets = (clone $ticketQuery)
            ->whereHas('status', function ($query) {
                $query->where('Name', 'Cancelled');
            })
            ->count();

        // =====================================================
        // RESOLUTION STATISTICS
        // =====================================================

        $resolutionRate = $totalTickets > 0
            ? round(($resolvedTickets / $totalTickets) * 100, 1)
            : 0;

        $unresolvedTickets = $totalTickets - $resolvedTickets;

        // =====================================================
        // RECENT TICKETS
        // =====================================================

        $recentTickets = (clone $ticketQuery)
            ->with([
                'priority',
                'status',
                'category',
                'currentAssignment.assignedTo'
            ])
            ->orderBy('CreatedAt', 'desc')
            ->take(5)
            ->get();

        // =====================================================
        // STATUS CHART
        // =====================================================

        $statusQuery = Ticket::selectRaw(
            '"TicketStatuses"."Name" as status_name,
            COUNT("Tickets"."Id") as total'
        )
        ->join(
            'TicketStatuses',
            'Tickets.StatusId',
            '=',
            'TicketStatuses.Id'
        );

        if ($fromDate) {
            $statusQuery->whereDate(
                'Tickets.CreatedAt',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $statusQuery->whereDate(
                'Tickets.CreatedAt',
                '<=',
                $toDate
            );
        }

        $statusData = $statusQuery
            ->groupBy(
                'TicketStatuses.Id',
                'TicketStatuses.Name'
            )
            ->orderBy('TicketStatuses.Id')
            ->get();

        $statusLabels = $statusData
            ->pluck('status_name')
            ->values();

        $statusCounts = $statusData
            ->pluck('total')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();

        // =====================================================
        // PRIORITY CHART
        // =====================================================

        $priorityQuery = Ticket::selectRaw(
            '"TicketPriorities"."Name" as priority_name,
            COUNT("Tickets"."Id") as total'
        )
        ->join(
            'TicketPriorities',
            'Tickets.PriorityId',
            '=',
            'TicketPriorities.Id'
        );

        if ($fromDate) {
            $priorityQuery->whereDate(
                'Tickets.CreatedAt',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $priorityQuery->whereDate(
                'Tickets.CreatedAt',
                '<=',
                $toDate
            );
        }

        $priorityData = $priorityQuery
            ->groupBy(
                'TicketPriorities.Id',
                'TicketPriorities.Name'
            )
            ->orderBy('TicketPriorities.Id')
            ->get();

        $priorityLabels = $priorityData
            ->pluck('priority_name')
            ->values();

        $priorityCounts = $priorityData
            ->pluck('total')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();

        // =====================================================
        // AGENT WORKLOAD
        // =====================================================

        $agents = User::whereHas('role', function ($query) {
            $query->where('Name', 'IT Support');
        })
        ->withCount([
            'ticketAssignments as active_tickets_count' => function ($query) {
                $query->where('IsCurrent', true);
            }
        ])
        ->orderByDesc('active_tickets_count')
        ->orderBy('Name')
        ->get();

        $agentLabels = $agents
            ->pluck('Name')
            ->values();

        $agentTicketCounts = $agents
            ->pluck('active_tickets_count')
            ->map(function ($value) {
                return (int) $value;
            })
            ->values();

        // =====================================================
        // RETURN REPORT VIEW
        // =====================================================

        return view('reports.index', compact(
            'fromDate',
            'toDate',

            'totalTickets',
            'openTickets',
            'pendingTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'cancelledTickets',

            'resolutionRate',
            'unresolvedTickets',

            'recentTickets',

            'statusLabels',
            'statusCounts',

            'priorityLabels',
            'priorityCounts',

            'agentLabels',
            'agentTicketCounts'
        ));
    }


    // =========================================================
    // EXPORT EXCEL
    // =========================================================

    public function exportExcel(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $fileName = 'IT-Help-Desk-Report';

        if ($fromDate && $toDate) {
            $fileName .= '-' . $fromDate . '-to-' . $toDate;
        } elseif ($fromDate) {
            $fileName .= '-from-' . $fromDate;
        } elseif ($toDate) {
            $fileName .= '-until-' . $toDate;
        }

        $fileName .= '.xlsx';

        return Excel::download(
            new ReportsExport($fromDate, $toDate),
            $fileName
        );
    }


    // =========================================================
    // EXPORT PDF
    // =========================================================

    public function exportPdf(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        // =====================================================
        // GET FILTERED TICKETS
        // =====================================================

        $query = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo',
        ]);

        if ($fromDate) {
            $query->whereDate(
                'CreatedAt',
                '>=',
                $fromDate
            );
        }

        if ($toDate) {
            $query->whereDate(
                'CreatedAt',
                '<=',
                $toDate
            );
        }

        $tickets = $query
            ->orderBy('CreatedAt', 'desc')
            ->get();

        // =====================================================
        // CALCULATE STATISTICS
        // =====================================================

        $totalTickets = $tickets->count();

        $openTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'Open';
        })->count();

        $pendingTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'Pending';
        })->count();

        $inProgressTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'In Progress';
        })->count();

        $resolvedTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'Resolved';
        })->count();

        $closedTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'Closed';
        })->count();

        $cancelledTickets = $tickets->filter(function ($ticket) {
            return $ticket->status &&
                $ticket->status->Name === 'Cancelled';
        })->count();

        $resolutionRate = $totalTickets > 0
            ? round(($resolvedTickets / $totalTickets) * 100, 1)
            : 0;

        $unresolvedTickets = $totalTickets - $resolvedTickets;

        // =====================================================
        // PDF DATA
        // =====================================================

        $data = compact(
            'tickets',
            'fromDate',
            'toDate',

            'totalTickets',
            'openTickets',
            'pendingTickets',
            'inProgressTickets',
            'resolvedTickets',
            'closedTickets',
            'cancelledTickets',

            'resolutionRate',
            'unresolvedTickets'
        );

        // =====================================================
        // PDF FILE NAME
        // =====================================================

        $fileName = 'IT-Help-Desk-Report';

        if ($fromDate && $toDate) {
            $fileName .= '-' . $fromDate . '-to-' . $toDate;
        } elseif ($fromDate) {
            $fileName .= '-from-' . $fromDate;
        } elseif ($toDate) {
            $fileName .= '-until-' . $toDate;
        }

        $fileName .= '.pdf';

        // =====================================================
        // GENERATE PDF
        // =====================================================

        $pdf = Pdf::loadView(
            'reports.pdf',
            $data
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}
