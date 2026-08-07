@extends('layouts.app')

@section('title', 'Admin Dashboard | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endsection

@section('page-title', 'Admin Dashboard')

@section(
    'page-description',
    'Monitor support activity, ticket workload, and system performance.'
)

@section('dashboard-active', 'active')


@section('content')

{{-- ========================================================= --}}
{{-- KPI CARDS --}}
{{-- ========================================================= --}}

<div class="admin-stats">

    {{-- TOTAL TICKETS --}}
    <div class="admin-stat-card">

        <div>

            <span>Total Tickets</span>

            <h2>
                {{ $totalTickets }}
            </h2>

            <p>
                All support requests
            </p>

        </div>

        <div class="stat-icon">
            🎫
        </div>

    </div>


    {{-- OPEN TICKETS --}}
    <div class="admin-stat-card">

        <div>

            <span>Open Tickets</span>

            <h2>
                {{ $openTickets }}
            </h2>

            <p>
                Waiting for resolution
            </p>

        </div>

        <div class="stat-icon">
            📂
        </div>

    </div>


    {{-- UNASSIGNED TICKETS --}}
    <div class="admin-stat-card warning-card">

        <div>

            <span>Unassigned</span>

            <h2>
                {{ $unassignedTickets }}
            </h2>

            <p>
                Require agent assignment
            </p>

        </div>

        <div class="stat-icon">
            👤
        </div>

    </div>


    {{-- CRITICAL TICKETS --}}
    <div class="admin-stat-card critical-card">

        <div>

            <span>Critical Tickets</span>

            <h2>
                {{ $criticalTickets }}
            </h2>

            <p>
                Require immediate attention
            </p>

        </div>

        <div class="stat-icon">
            ⚠️
        </div>

    </div>

</div>



{{-- ========================================================= --}}
{{-- DASHBOARD GRID --}}
{{-- ========================================================= --}}

<div class="admin-dashboard-grid">


    {{-- ===================================================== --}}
    {{-- RECENT TICKETS --}}
    {{-- ===================================================== --}}

    <section class="admin-card recent-tickets-card">

        <div class="admin-card-header">

            <div>

                <h2>
                    Recent Tickets
                </h2>

                <p>
                    Latest support requests submitted by employees.
                </p>

            </div>


            <a href="{{ route('allticketspage') }}">
                View All
            </a>

        </div>


        <div class="admin-table-wrapper">

            <table class="admin-table">

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

                    </tr>

                </thead>


                <tbody>

                    @forelse($recentTickets as $ticket)

                        <tr>

                            {{-- REFERENCE NUMBER --}}
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

                                @php

                                    $priorityName =
                                        strtolower(
                                            $ticket->priority->Name ?? ''
                                        );

                                @endphp


                                <span class="
                                    admin-badge
                                    priority-{{ $priorityName }}
                                ">

                                    {{ $ticket->priority->Name ?? 'Unknown' }}

                                </span>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                @php

                                    $statusName =
                                        $ticket->status->Name ?? 'Unknown';

                                    $statusClass =
                                        strtolower(
                                            str_replace(
                                                ' ',
                                                '-',
                                                $statusName
                                            )
                                        );

                                @endphp


                                <span class="
                                    admin-badge
                                    status-{{ $statusClass }}
                                ">

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

                        </tr>


                    @empty

                        <tr>

                            <td colspan="5" class="text-center">

                                No tickets found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </section>



    {{-- ===================================================== --}}
    {{-- QUICK ACTIONS --}}
    {{-- ===================================================== --}}

    <section class="admin-card quick-actions-card">

        <div class="admin-card-header">

            <div>

                <h2>
                    Quick Actions
                </h2>

                <p>
                    Common administration tasks.
                </p>

            </div>

        </div>


        <div class="quick-actions">


            {{-- MANAGE TICKETS --}}
            <a
                href="{{ route('allticketspage') }}"
                class="quick-action"
            >

                <span>
                    🎫
                </span>

                <div>

                    <strong>
                        Manage Tickets
                    </strong>

                    <p>
                        Assign and monitor support tickets.
                    </p>

                </div>

            </a>


            {{-- MANAGE USERS --}}
            <a
                href="{{ route('UserManagementpage') }}"
                class="quick-action"
            >

                <span>
                    👥
                </span>

                <div>

                    <strong>
                        Manage Users
                    </strong>

                    <p>
                        View users and manage roles.
                    </p>

                </div>

            </a>


            {{-- REPORTS --}}
            <a
                href="/reports"
                class="quick-action"
            >

                <span>
                    📊
                </span>

                <div>

                    <strong>
                        View Reports
                    </strong>

                    <p>
                        Review support performance.
                    </p>

                </div>

            </a>


            {{-- ACTIVITY LOG --}}
            <a
                href="{{ route('activity.logs') }}"
                class="quick-action"
            >

                <span>
                    📋
                </span>

                <div>

                    <strong>
                        Activity Log
                    </strong>

                    <p>
                        Review recent system activity.
                    </p>

                </div>

            </a>

        </div>

    </section>

</div>



{{-- ========================================================= --}}
{{-- AGENT WORKLOAD --}}
{{-- ========================================================= --}}

<section class="admin-card agent-workload-card">

    <div class="admin-card-header">

        <div>

            <h2>
                Agent Workload
            </h2>

            <p>
                Current ticket distribution across support agents.
            </p>

        </div>

    </div>


    <div class="workload-grid">

        @forelse($agents as $agent)

            @php

                /*
                |--------------------------------------------------------------------------
                | Generate Agent Initials
                |--------------------------------------------------------------------------
                */

                $nameParts = explode(
                    ' ',
                    trim($agent->Name)
                );

                $initials = '';

                foreach ($nameParts as $part) {

                    if (!empty($part)) {

                        $initials .= strtoupper(
                            substr($part, 0, 1)
                        );

                    }

                    if (strlen($initials) >= 2) {
                        break;
                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Workload Level
                |--------------------------------------------------------------------------
                |
                | 0 - 3 tickets  = Low
                | 4 - 7 tickets  = Medium
                | 8+ tickets     = High
                |
                */

                $activeTickets =
                    $agent->active_tickets_count;


                if ($activeTickets >= 8) {

                    $workloadClass =
                        'workload-high';

                } elseif ($activeTickets >= 4) {

                    $workloadClass =
                        'workload-medium';

                } else {

                    $workloadClass =
                        'workload-low';

                }

            @endphp


            <div class="agent-workload">


                {{-- AGENT INFORMATION --}}
                <div class="agent-info">

                    <div class="agent-avatar">

                        {{ $initials }}

                    </div>


                    <div>

                        <strong>

                            {{ $agent->Name }}

                        </strong>

                        <span>
                            IT Support Agent
                        </span>

                    </div>

                </div>



                {{-- WORKLOAD --}}
                <div class="workload-details">

                    <span>

                        {{ $activeTickets }}

                        {{ $activeTickets == 1 ? 'active ticket' : 'active tickets' }}

                    </span>


                    <div class="workload-track">

                        <div
                            class="workload-fill {{ $workloadClass }}"
                        ></div>

                    </div>

                </div>

            </div>


        @empty

            <div>

                <p>
                    No IT Support agents found.
                </p>

            </div>

        @endforelse

    </div>

</section>

@endsection
