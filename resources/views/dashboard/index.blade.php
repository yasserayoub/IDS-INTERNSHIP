@extends('layouts.app')

@section('title', 'Dashboard | IT Help Desk')

@section('page-title', 'IT Manager Dashboard')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section(
    'page-description',
    "Monitor ticket activity, support workload, and help desk performance."
)

@section('dashboard-active', 'active')


@section('content')


{{-- ========================================================= --}}
{{-- KPI / STATISTICS CARDS --}}
{{-- ========================================================= --}}

<div class="stats-grid">

    {{-- OPEN TICKETS --}}
    <div class="stat-card">
        <p>Open Tickets</p>

        <h2>
            {{ $openTickets }}
        </h2>

        <span>
            Waiting to be handled
        </span>
    </div>


    {{-- PENDING TICKETS --}}
    <div class="stat-card">
        <p>Pending Tickets</p>

        <h2>
            {{ $pendingTickets }}
        </h2>

        <span>
            Waiting for further action
        </span>
    </div>


    {{-- RESOLVED TICKETS --}}
    <div class="stat-card">
        <p>Resolved Tickets</p>

        <h2>
            {{ $resolvedTickets }}
        </h2>

        <span>
            Successfully resolved
        </span>
    </div>


    {{-- TOTAL TICKETS --}}
    <div class="stat-card">
        <p>Total Tickets</p>

        <h2>
            {{ $totalTickets }}
        </h2>

        <span>
            All support requests
        </span>
    </div>

</div>



{{-- ========================================================= --}}
{{-- ANALYTICS CHARTS --}}
{{-- ========================================================= --}}

<div class="analytics-grid">


    {{-- ===================================================== --}}
    {{-- STATUS DISTRIBUTION --}}
    {{-- ===================================================== --}}

    <section class="panel chart-panel">

        <div class="panel-header">

            <div>
                <h2>Ticket Status</h2>

                <p>
                    Distribution of tickets by current status.
                </p>
            </div>

        </div>


        <div class="chart-container">

            <canvas id="statusChart"></canvas>

        </div>

    </section>



    {{-- ===================================================== --}}
    {{-- PRIORITY DISTRIBUTION --}}
    {{-- ===================================================== --}}

    <section class="panel chart-panel">

        <div class="panel-header">

            <div>
                <h2>Tickets by Priority</h2>

                <p>
                    Distribution of tickets by priority level.
                </p>
            </div>

        </div>


        <div class="chart-container">

            <canvas id="priorityChart"></canvas>

        </div>

    </section>

</div>



{{-- ========================================================= --}}
{{-- AGENT WORKLOAD CHART --}}
{{-- ========================================================= --}}

<div class="dashboard-panels">

    <section class="panel">

        <div class="panel-header">

            <div>
                <h2>Agent Workload</h2>

                <p>
                    Number of active tickets currently assigned to each
                    IT Support agent.
                </p>
            </div>

        </div>


        <div class="agent-chart-container">

            <canvas id="agentWorkloadChart"></canvas>

        </div>

    </section>

</div>



{{-- ========================================================= --}}
{{-- RECENT TICKETS --}}
{{-- ========================================================= --}}

<div class="dashboard-panels">

    <section class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Recent Tickets
                </h2>

                <p>
                    Latest support requests submitted to the help desk.
                </p>

            </div>


            <a href="{{ route('allticketspage') }}">
                View All
            </a>

        </div>


        <div class="table-responsive">

            <table>

                <thead>

                    <tr>

                        <th>Ticket</th>

                        <th>Title</th>

                        <th>Priority</th>

                        <th>Status</th>

                        <th>Assigned To</th>

                        <th>Created</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentTickets as $ticket)

                        @php

                            // Priority CSS class
                            $priorityName = strtolower(
                                $ticket->priority->Name ?? 'unknown'
                            );


                            // Status CSS class
                            $statusName =
                                $ticket->status->Name ?? 'Unknown';

                            $statusClass = strtolower(
                                str_replace(
                                    ' ',
                                    '-',
                                    $statusName
                                )
                            );

                        @endphp


                        <tr>


                            {{-- TICKET REFERENCE --}}
                            <td>

                                <strong>
                                    {{ $ticket->ReferenceNumber }}
                                </strong>

                            </td>


                            {{-- TITLE --}}
                            <td>

                                {{ $ticket->Title }}

                            </td>


                            {{-- PRIORITY --}}
                            <td>

                                <span
                                    class="badge priority-{{ $priorityName }}"
                                >

                                    {{ $ticket->priority->Name ?? 'Unknown' }}

                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span
                                    class="badge status-{{ $statusClass }}"
                                >

                                    {{ $statusName }}

                                </span>

                            </td>


                            {{-- ASSIGNED AGENT --}}
                            <td>

                                @if(
                                    $ticket->currentAssignment &&
                                    $ticket->currentAssignment->assignedTo
                                )

                                    {{ $ticket->currentAssignment->assignedTo->Name }}

                                @else

                                    <span class="unassigned-text">
                                        Unassigned
                                    </span>

                                @endif

                            </td>


                            {{-- CREATED DATE --}}
                            <td>

                                {{ $ticket->CreatedAt->format('M j, Y') }}

                            </td>


                            {{-- VIEW TICKET --}}
                            <td>

                                <a
                                    href="{{ route('manager.ticket.show', $ticket->Id) }}"
                                    class="view-ticket-button"
                                >
                                    View
                                </a>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="text-center"
                            >

                                No tickets found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>



{{-- ========================================================= --}}
{{-- TICKET OVERVIEW --}}
{{-- ========================================================= --}}

<div class="dashboard-panels">

    <section class="panel">

        <div class="panel-header">

            <div>

                <h2>
                    Ticket Overview
                </h2>

                <p>
                    Current distribution of support tickets.
                </p>

            </div>

        </div>


        <div class="ticket-overview-grid">


            {{-- OPEN --}}
            <div class="overview-item">

                <span>
                    Open
                </span>

                <strong>
                    {{ $openTickets }}
                </strong>

            </div>


            {{-- PENDING --}}
            <div class="overview-item">

                <span>
                    Pending
                </span>

                <strong>
                    {{ $pendingTickets }}
                </strong>

            </div>


            {{-- RESOLVED --}}
            <div class="overview-item">

                <span>
                    Resolved
                </span>

                <strong>
                    {{ $resolvedTickets }}
                </strong>

            </div>


            {{-- TOTAL --}}
            <div class="overview-item">

                <span>
                    Total
                </span>

                <strong>
                    {{ $totalTickets }}
                </strong>

            </div>


        </div>

    </section>

</div>


@endsection



{{-- ========================================================= --}}
{{-- PAGE JAVASCRIPT --}}
{{-- ========================================================= --}}

@section('page-js')

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | Data coming from Laravel
    |--------------------------------------------------------------------------
    */

    const statusLabels = @json($statusLabels);
    const statusCounts = @json($statusCounts);

    const priorityLabels = @json($priorityLabels);
    const priorityCounts = @json($priorityCounts);

    const agentLabels = @json($agentLabels);
    const agentTicketCounts = @json($agentTicketCounts);



    /*
    |--------------------------------------------------------------------------
    | STATUS DOUGHNUT CHART
    |--------------------------------------------------------------------------
    */

    const statusCanvas = document.getElementById('statusChart');

    if (statusCanvas) {

        new Chart(statusCanvas, {

            type: 'doughnut',

            data: {

                labels: statusLabels,

                datasets: [{

                    label: 'Tickets',

                    data: statusCounts,

                    backgroundColor: [
                        '#3b82f6',
                        '#6366f1',
                        '#f59e0b',
                        '#22c55e',
                        '#64748b',
                        '#8b5cf6',
                        '#ef4444'
                    ],

                    borderWidth: 2,

                    borderColor: '#ffffff'

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }

                    }

                }

            }

        });

    }



    /*
    |--------------------------------------------------------------------------
    | PRIORITY DOUGHNUT CHART
    |--------------------------------------------------------------------------
    */

    const priorityCanvas = document.getElementById('priorityChart');

    if (priorityCanvas) {

        new Chart(priorityCanvas, {

            type: 'doughnut',

            data: {

                labels: priorityLabels,

                datasets: [{

                    label: 'Tickets',

                    data: priorityCounts,

                    backgroundColor: [
                        '#22c55e',
                        '#f59e0b',
                        '#f97316',
                        '#ef4444'
                    ],

                    borderWidth: 2,

                    borderColor: '#ffffff'

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom',

                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }

                    }

                }

            }

        });

    }



    const agentCanvas =
        document.getElementById('agentWorkloadChart');

    if (agentCanvas) {

        new Chart(agentCanvas, {

            type: 'bar',

            data: {

                labels: agentLabels,

                datasets: [{

                    label: 'Active Tickets',

                    data: agentTicketCounts,

                    backgroundColor: '#2563eb',

                    borderRadius: 6,

                    maxBarThickness: 50

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    y: {

                        beginAtZero: true,

                        ticks: {

                            precision: 0

                        }

                    }

                },

                plugins: {

                    legend: {

                        display: false

                    }

                }

            }

        });

    }

});

</script>

@endsection
