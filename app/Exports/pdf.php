<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">

    <title>IT Help Desk Report</title>

    <style>

        @page {
            margin: 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #1f2937;
            margin: 0;
        }

        .header {
            width: 100%;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left {
            width: 70%;
        }

        .header-right {
            width: 30%;
            text-align: right;
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            color: #111827;
            margin: 0;
        }

        .subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
        }

        .date-range {
            font-size: 10px;
            color: #374151;
        }

        .section-title {
            font-size: 15px;
            font-weight: bold;
            color: #111827;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 7px;
            margin-left: -7px;
        }

        .stat-box {
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            padding: 12px;
            text-align: center;
            width: 14.28%;
        }

        .stat-number {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
        }

        .stat-label {
            font-size: 8px;
            color: #6b7280;
            margin-top: 4px;
        }

        .performance {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .performance td {
            border: 1px solid #e5e7eb;
            padding: 10px;
        }

        .performance-label {
            color: #6b7280;
            width: 60%;
        }

        .performance-value {
            font-size: 14px;
            font-weight: bold;
            text-align: right;
        }

        .ticket-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .ticket-table th {
            background: #1f2937;
            color: white;
            padding: 8px 6px;
            text-align: left;
            font-size: 8px;
        }

        .ticket-table td {
            border: 1px solid #e5e7eb;
            padding: 7px 6px;
            font-size: 8px;
        }

        .ticket-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .status {
            font-weight: bold;
        }

        .footer {
            margin-top: 20px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            color: #6b7280;
            font-size: 8px;
            text-align: center;
        }

    </style>

</head>

<body>

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td class="header-left">

                    <div class="title">
                        IT Help Desk Report
                    </div>

                    <div class="subtitle">
                        Ticket activity and support performance overview
                    </div>

                </td>

                <td class="header-right">

                    <div class="date-range">

                        @if($fromDate && $toDate)

                            {{ $fromDate }} to {{ $toDate }}

                        @elseif($fromDate)

                            From {{ $fromDate }}

                        @elseif($toDate)

                            Until {{ $toDate }}

                        @else

                            All Tickets

                        @endif

                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- =====================================================
         SUMMARY
    ====================================================== --}}

    <div class="section-title">
        Ticket Summary
    </div>

    <table class="stats-table">

        <tr>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $totalTickets }}
                </div>

                <div class="stat-label">
                    TOTAL TICKETS
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $openTickets }}
                </div>

                <div class="stat-label">
                    OPEN
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $inProgressTickets }}
                </div>

                <div class="stat-label">
                    IN PROGRESS
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $pendingTickets }}
                </div>

                <div class="stat-label">
                    PENDING
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $resolvedTickets }}
                </div>

                <div class="stat-label">
                    RESOLVED
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $closedTickets }}
                </div>

                <div class="stat-label">
                    CLOSED
                </div>
            </td>

            <td class="stat-box">
                <div class="stat-number">
                    {{ $cancelledTickets }}
                </div>

                <div class="stat-label">
                    CANCELLED
                </div>
            </td>

        </tr>

    </table>


    {{-- =====================================================
         PERFORMANCE
    ====================================================== --}}

    <div class="section-title">
        Resolution Overview
    </div>

    <table class="performance">

        <tr>

            <td class="performance-label">
                Resolution Rate
            </td>

            <td class="performance-value">
                {{ $resolutionRate }}%
            </td>

        </tr>

        <tr>

            <td class="performance-label">
                Resolved Tickets
            </td>

            <td class="performance-value">
                {{ $resolvedTickets }}
            </td>

        </tr>

        <tr>

            <td class="performance-label">
                Unresolved Tickets
            </td>

            <td class="performance-value">
                {{ $unresolvedTickets }}
            </td>

        </tr>

    </table>


    {{-- =====================================================
         TICKET DETAILS
    ====================================================== --}}

    <div class="section-title">
        Ticket Details
    </div>

    @if($tickets->count())

        <table class="ticket-table">

            <thead>

                <tr>

                    <th>Ticket</th>

                    <th>Title</th>

                    <th>Created By</th>

                    <th>Category</th>

                    <th>Priority</th>

                    <th>Status</th>

                    <th>Assigned To</th>

                    <th>Created</th>

                </tr>

            </thead>

            <tbody>

                @foreach($tickets as $ticket)

                    <tr>

                        <td>
                            {{ $ticket->ReferenceNumber ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $ticket->Title ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $ticket->creator
                                ? $ticket->creator->Name
                                : 'Unknown'
                            }}
                        </td>

                        <td>
                            {{ $ticket->category
                                ? $ticket->category->Name
                                : 'N/A'
                            }}
                        </td>

                        <td>
                            {{ $ticket->priority
                                ? $ticket->priority->Name
                                : 'N/A'
                            }}
                        </td>

                        <td class="status">
                            {{ $ticket->status
                                ? $ticket->status->Name
                                : 'N/A'
                            }}
                        </td>

                        <td>
                            @if(
                                $ticket->currentAssignment &&
                                $ticket->currentAssignment->assignedTo
                            )

                                {{ $ticket->currentAssignment->assignedTo->Name }}

                            @else

                                Not Assigned

                            @endif
                        </td>

                        <td>
                            {{ $ticket->CreatedAt
                                ? $ticket->CreatedAt->format('Y-m-d H:i')
                                : 'N/A'
                            }}
                        </td>

                    </tr>

                @endforeach

            </tbody>

        </table>

    @else

        <table class="ticket-table">

            <tr>

                <td style="text-align:center; padding:20px;">
                    No tickets were found for the selected period.
                </td>

            </tr>

        </table>

    @endif


    {{-- =====================================================
         FOOTER
    ====================================================== --}}

    <div class="footer">

        IT Help Desk &nbsp;|&nbsp;
        Generated {{ now()->format('Y-m-d H:i') }}

    </div>

</body>
</html>
