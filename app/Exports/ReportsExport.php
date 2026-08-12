<?php

namespace App\Exports;

use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReportsExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected $fromDate;
    protected $toDate;

    public function __construct($fromDate = null, $toDate = null)
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    // =========================================================
    // GET TICKETS
    // =========================================================

    public function collection()
    {
        $query = Ticket::with([
            'creator',
            'category',
            'priority',
            'status',
            'currentAssignment.assignedTo',
        ]);

        // =====================================================
        // FROM DATE
        // =====================================================

        if ($this->fromDate) {
            $query->whereDate(
                'CreatedAt',
                '>=',
                $this->fromDate
            );
        }

        // =====================================================
        // TO DATE
        // =====================================================

        if ($this->toDate) {
            $query->whereDate(
                'CreatedAt',
                '<=',
                $this->toDate
            );
        }

        // =====================================================
        // RETURN TICKETS
        // =====================================================

        return $query
            ->orderBy('CreatedAt', 'desc')
            ->get();
    }

    // =========================================================
    // EXCEL HEADINGS
    // =========================================================

    public function headings(): array
    {
        return [
            'Ticket Number',
            'Title',
            'Created By',
            'Category',
            'Priority',
            'Status',
            'Assigned To',
            'Created Date',
        ];
    }

    // =========================================================
    // MAP TICKET DATA
    // =========================================================

    public function map($ticket): array
    {
        return [

            // Ticket Number
            $ticket->ReferenceNumber ?? 'N/A',

            // Title
            $ticket->Title ?? 'N/A',

            // Created By
            $ticket->creator
                ? $ticket->creator->Name
                : 'Unknown',

            // Category
            $ticket->category
                ? $ticket->category->Name
                : 'N/A',

            // Priority
            $ticket->priority
                ? $ticket->priority->Name
                : 'N/A',

            // Status
            $ticket->status
                ? $ticket->status->Name
                : 'N/A',

            // Assigned To
            $ticket->currentAssignment &&
            $ticket->currentAssignment->assignedTo
                ? $ticket->currentAssignment->assignedTo->Name
                : 'Not Assigned',

            // Created Date
            $ticket->CreatedAt
                ? $ticket->CreatedAt->format('Y-m-d H:i')
                : 'N/A',
        ];
    }
}
