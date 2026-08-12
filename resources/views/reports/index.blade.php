@extends('layouts.app')

@section('title', 'Reports | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endsection

@section('page-title', 'Reports')

@section(
    'page-description',
    'Overview of help desk performance, ticket activity, priorities, and agent workload.'
)

@section('content')

<div class="reports-page">

    {{-- =====================================================
         REPORT HEADER
    ====================================================== --}}

    <div class="report-header">

        <div class="report-header-content">

            <div class="report-header-icon">
                📊
            </div>

            <div>

                <h1>
                    Help Desk Reports
                </h1>

                <p>
                    Analyze ticket activity and support performance.
                </p>

            </div>

        </div>

    </div>


    {{-- =====================================================
         DATE FILTER
    ====================================================== --}}

    <section class="report-card filter-card">

        <div class="report-card-header">

            <div>

                <span class="section-eyebrow">
                    REPORT PERIOD
                </span>

                <h2>
                    Filter Report
                </h2>

                <p>
                    Select a date range to analyze tickets created
                    during a specific period.
                </p>

            </div>

        </div>


        <form
            method="GET"
            action="{{ route('reports.index') }}"
            class="report-filter-form"
        >

            {{-- FROM DATE --}}

            <div class="date-field">

                <label for="from_date">
                    From Date
                </label>

                <div class="date-input-wrapper">

                    <span class="date-icon">
                        📅
                    </span>

                    <input
                        type="date"
                        id="from_date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                    >

                </div>

            </div>


            {{-- TO DATE --}}

            <div class="date-field">

                <label for="to_date">
                    To Date
                </label>

                <div class="date-input-wrapper">

                    <span class="date-icon">
                        📅
                    </span>

                    <input
                        type="date"
                        id="to_date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                    >

                </div>

            </div>


            {{-- ACTION BUTTONS --}}

            <div class="filter-actions">

                <button
                    type="submit"
                    class="report-button primary"
                >
                    Apply Filter
                </button>


                @if(request('from_date') || request('to_date'))

                    <a
                        href="{{ route('reports.index') }}"
                        class="report-button secondary"
                    >
                        Reset
                    </a>

                @endif


                {{-- EXPORT EXCEL --}}

                <a
                    href="{{ route('reports.export.excel', [
                        'from_date' => request('from_date'),
                        'to_date' => request('to_date'),
                    ]) }}"
                    class="export-excel-btn"
                >
                    📊
                    Export Excel
                </a>
                <a
    href="{{ route('reports.export.pdf', [
        'from_date' => request('from_date'),
        'to_date' => request('to_date'),
    ]) }}"
    class="export-pdf-btn"
>
    <span>📄</span>
    Export PDF
</a>

            </div>

        </form>


        {{-- ACTIVE FILTER INFORMATION --}}

        @if(request('from_date') || request('to_date'))

            <div class="active-filter">

                <span class="active-filter-dot"></span>

                <span>
                    Showing tickets from

                    <strong>
                        {{ request('from_date') ?: 'Beginning' }}
                    </strong>

                    to

                    <strong>
                        {{ request('to_date') ?: 'Today' }}
                    </strong>
                </span>

            </div>

        @endif

    </section>


    {{-- =====================================================
         KPI OVERVIEW
    ====================================================== --}}

    <div class="kpi-grid">


        {{-- TOTAL TICKETS --}}

        <div class="kpi-card">

            <div class="kpi-icon blue">
                🎫
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Total Tickets
                </span>

                <strong class="kpi-value">
                    {{ $totalTickets }}
                </strong>

                <span class="kpi-description">
                    All tickets
                </span>

            </div>

        </div>


        {{-- OPEN TICKETS --}}

        <div class="kpi-card">

            <div class="kpi-icon orange">
                🔓
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Open Tickets
                </span>

                <strong class="kpi-value">
                    {{ $openTickets }}
                </strong>

                <span class="kpi-description">
                    Currently open
                </span>

            </div>

        </div>


        {{-- IN PROGRESS --}}

        <div class="kpi-card">

            <div class="kpi-icon purple">
                ⚙️
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    In Progress
                </span>

                <strong class="kpi-value">
                    {{ $inProgressTickets }}
                </strong>

                <span class="kpi-description">
                    Being handled
                </span>

            </div>

        </div>


        {{-- PENDING --}}

        <div class="kpi-card">

            <div class="kpi-icon yellow">
                ⏳
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Pending
                </span>

                <strong class="kpi-value">
                    {{ $pendingTickets }}
                </strong>

                <span class="kpi-description">
                    Waiting for action
                </span>

            </div>

        </div>


        {{-- RESOLVED --}}

        <div class="kpi-card">

            <div class="kpi-icon green">
                ✓
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Resolved
                </span>

                <strong class="kpi-value">
                    {{ $resolvedTickets }}
                </strong>

                <span class="kpi-description">
                    Successfully resolved
                </span>

            </div>

        </div>


        {{-- CLOSED --}}

        <div class="kpi-card">

            <div class="kpi-icon gray">
                🔒
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Closed
                </span>

                <strong class="kpi-value">
                    {{ $closedTickets }}
                </strong>

                <span class="kpi-description">
                    Completed tickets
                </span>

            </div>

        </div>


        {{-- CANCELLED --}}

        <div class="kpi-card">

            <div class="kpi-icon red">
                ✕
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Cancelled
                </span>

                <strong class="kpi-value">
                    {{ $cancelledTickets }}
                </strong>

                <span class="kpi-description">
                    Cancelled tickets
                </span>

            </div>

        </div>


        {{-- UNRESOLVED --}}

        <div class="kpi-card">

            <div class="kpi-icon dark">
                !
            </div>

            <div class="kpi-content">

                <span class="kpi-label">
                    Unresolved
                </span>

                <strong class="kpi-value">
                    {{ $unresolvedTickets }}
                </strong>

                <span class="kpi-description">
                    Still requiring action
                </span>

            </div>

        </div>

    </div>


    {{-- =====================================================
         PERFORMANCE SUMMARY
    ====================================================== --}}

    <section class="report-card">

        <div class="report-card-header">

            <div>

                <span class="section-eyebrow">
                    PERFORMANCE SUMMARY
                </span>

                <h2>
                    Resolution Overview
                </h2>

                <p>
                    Summary of ticket resolution performance.
                </p>

            </div>

        </div>


        <div class="performance-grid">


            {{-- RESOLUTION RATE --}}

            <div class="performance-item">

                <div class="performance-top">

                    <span>
                        Resolution Rate
                    </span>

                    <strong>
                        {{ $resolutionRate }}%
                    </strong>

                </div>

                <div class="progress-track">

                    <div
                        class="progress-bar"
                        style="width: {{ min($resolutionRate, 100) }}%;"
                    ></div>

                </div>

                <small>
                    Percentage of all tickets that have been resolved.
                </small>

            </div>


            {{-- RESOLVED --}}

            <div class="performance-stat">

                <div class="stat-icon green">
                    ✓
                </div>

                <div>

                    <span>
                        Resolved
                    </span>

                    <strong>
                        {{ $resolvedTickets }}
                    </strong>

                </div>

            </div>


            {{-- UNRESOLVED --}}

            <div class="performance-stat">

                <div class="stat-icon orange">
                    !
                </div>

                <div>

                    <span>
                        Unresolved
                    </span>

                    <strong>
                        {{ $unresolvedTickets }}
                    </strong>

                </div>

            </div>

        </div>

    </section>


    {{-- =====================================================
         CHARTS
    ====================================================== --}}

    <div class="charts-grid">


        {{-- STATUS DISTRIBUTION --}}

        <section class="report-card chart-card">

            <div class="report-card-header">

                <div>

                    <span class="section-eyebrow">
                        TICKET ANALYSIS
                    </span>

                    <h2>
                        Ticket Status Distribution
                    </h2>

                    <p>
                        Breakdown of tickets by current status.
                    </p>

                </div>

            </div>


            <div class="chart-wrapper">

                <canvas id="statusChart"></canvas>

            </div>

        </section>


        {{-- PRIORITY DISTRIBUTION --}}

        <section class="report-card chart-card">

            <div class="report-card-header">

                <div>

                    <span class="section-eyebrow">
                        PRIORITY ANALYSIS
                    </span>

                    <h2>
                        Ticket Priority Distribution
                    </h2>

                    <p>
                        Breakdown of tickets by priority.
                    </p>

                </div>

            </div>


            <div class="chart-wrapper">

                <canvas id="priorityChart"></canvas>

            </div>

        </section>

    </div>


    {{-- =====================================================
         AGENT WORKLOAD
    ====================================================== --}}

    <section class="report-card chart-card">

        <div class="report-card-header">

            <div>

                <span class="section-eyebrow">
                    SUPPORT PERFORMANCE
                </span>

                <h2>
                    IT Support Agent Workload
                </h2>

                <p>
                    Number of currently assigned tickets per support agent.
                </p>

            </div>

        </div>


        <div class="agent-chart-wrapper">

            <canvas id="agentWorkloadChart"></canvas>

        </div>

    </section>


    {{-- =====================================================
         RECENT TICKETS
    ====================================================== --}}

    <section class="report-card">

        <div class="report-card-header">

            <div>

                <span class="section-eyebrow">
                    RECENT ACTIVITY
                </span>

                <h2>
                    Recent Tickets
                </h2>

                <p>
                    The five most recently created tickets.
                </p>

            </div>

        </div>


        <div class="table-container">

            <table class="report-table">

                <thead>

                    <tr>

                        <th>
                            Ticket
                        </th>

                        <th>
                            Title
                        </th>

                        <th>
                            Priority
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Assigned To
                        </th>

                        <th>
                            Created
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentTickets as $ticket)

                        <tr>

                            {{-- TICKET --}}

                            <td>

                                <span class="ticket-reference">
                                    {{ $ticket->ReferenceNumber }}
                                </span>

                            </td>


                            {{-- TITLE --}}

                            <td>
                                {{ $ticket->Title }}
                            </td>


                            {{-- PRIORITY --}}

                            <td>

                                @php
                                    $priorityClass = match(
                                        $ticket->priority?->Name
                                    ) {
                                        'Low' => 'priority-low',
                                        'Medium' => 'priority-medium',
                                        'High' => 'priority-high',
                                        'Critical' => 'priority-critical',
                                        default => ''
                                    };
                                @endphp

                                <span
                                    class="priority-badge {{ $priorityClass }}"
                                >
                                    {{ $ticket->priority?->Name ?? 'N/A' }}
                                </span>

                            </td>


                            {{-- STATUS --}}

                            <td>

                                @php
                                    $statusClass = match(
                                        $ticket->status?->Name
                                    ) {
                                        'Open' => 'status-open',
                                        'In Progress' => 'status-progress',
                                        'Pending' => 'status-pending',
                                        'Resolved' => 'status-resolved',
                                        'Closed' => 'status-closed',
                                        'Cancelled' => 'status-cancelled',
                                        'Reopened' => 'status-reopened',
                                        default => ''
                                    };
                                @endphp

                                <span
                                    class="status-badge {{ $statusClass }}"
                                >
                                    {{ $ticket->status?->Name ?? 'N/A' }}
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

                                    <span class="not-assigned">
                                        Not Assigned
                                    </span>

                                @endif

                            </td>


                            {{-- CREATED DATE --}}

                            <td>

                                {{ $ticket->CreatedAt?->format('M j, Y') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="empty-state"
                            >

                                <div class="empty-icon">
                                    📭
                                </div>

                                <h3>
                                    No Tickets Found
                                </h3>

                                <p>
                                    No tickets were found for the selected period.
                                </p>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>

</div>


{{-- =====================================================
     CHART.JS
====================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

document.addEventListener('DOMContentLoaded', function () {


    /*
    |--------------------------------------------------------------------------
    | STATUS CHART
    |--------------------------------------------------------------------------
    */

    const statusCanvas =
        document.getElementById('statusChart');


    if (statusCanvas) {

        new Chart(statusCanvas, {

            type: 'doughnut',

            data: {

                labels: @json($statusLabels),

                datasets: [{

                    data: @json($statusCounts),

                    borderWidth: 2,

                }]

            },

            options: {

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {

                        position: 'bottom'

                    }

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | PRIORITY CHART
    |--------------------------------------------------------------------------
    */

    const priorityCanvas =
        document.getElementById('priorityChart');


    if (priorityCanvas) {

        new Chart(priorityCanvas, {

            type: 'bar',

            data: {

                labels: @json($priorityLabels),

                datasets: [{

                    label: 'Tickets',

                    data: @json($priorityCounts),

                    borderRadius: 6,

                    borderWidth: 0

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


    /*
    |--------------------------------------------------------------------------
    | AGENT WORKLOAD CHART
    |--------------------------------------------------------------------------
    */

    const agentCanvas =
        document.getElementById('agentWorkloadChart');


    if (agentCanvas) {

        new Chart(agentCanvas, {

            type: 'bar',

            data: {

                labels: @json($agentLabels),

                datasets: [{

                    label: 'Active Tickets',

                    data: @json($agentTicketCounts),

                    borderRadius: 6,

                    borderWidth: 0

                }]

            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                scales: {

                    x: {

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
