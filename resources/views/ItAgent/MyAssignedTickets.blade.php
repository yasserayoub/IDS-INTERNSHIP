@extends('layouts.app')

@section('title', 'My Assigned Tickets')

@section('content')
@section('page-css')
<link rel="stylesheet" href="{{ asset('css/ItAgent.css') }}">
@endsection

<div class="dashboard-container">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>My Assigned Tickets</h1>
            <p>View and manage tickets currently assigned to you.</p>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="summary-grid">

        <div class="summary-card">
            <h2>{{ $assignedCount ?? 0 }}</h2>
            <span>Total Assigned</span>
        </div>

        <div class="summary-card">
            <h2>{{ $openCount ?? 0 }}</h2>
            <span>Open</span>
        </div>

        <div class="summary-card">
            <h2>{{ $progressCount ?? 0 }}</h2>
            <span>In Progress</span>
        </div>

        <div class="summary-card">
            <h2>{{ $pendingCount ?? 0 }}</h2>
            <span>Pending</span>
        </div>

    </div>

    <!-- Ticket Panel -->
    <div class="panel">

        <div class="panel-header">

            <h2>Assigned Tickets</h2>

            <div class="toolbar">

                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search tickets..."
                >

                <select>
                    <option>All Statuses</option>
                    <option>Open</option>
                    <option>In Progress</option>
                    <option>Pending</option>
                    <option>Resolved</option>
                    <option>Closed</option>
                </select>

                <select>
                    <option>All Priorities</option>
                    <option>Low</option>
                    <option>Medium</option>
                    <option>High</option>
                    <option>Critical</option>
                </select>

                <button class="btn-filter">
                    Filter
                </button>

            </div>

        </div>

        <div class="table-container">

            <table class="tickets-table">

                <thead>

                    <tr>
                        <th>Reference #</th>
                        <th>Title</th>
                        <th>Employee</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($tickets as $ticket)

                        <tr>

                            <td>
                                {{ $ticket->ReferenceNumber }}
                            </td>

                            <td>
                                {{ $ticket->Title }}
                            </td>

                            <td>
                                {{ $ticket->creator->Name }}
                            </td>

                            <td>
                                {{ $ticket->category->Name }}
                            </td>

                            <td>

                                <span class="priority priority-{{ strtolower($ticket->priority->Name) }}">
                                    {{ $ticket->priority->Name }}
                                </span>

                            </td>

                            <td>

                                <span class="status status-{{ strtolower(str_replace(' ','-',$ticket->status->Name)) }}">
                                    {{ $ticket->status->Name }}
                                </span>

                            </td>

                            <td>
                                {{ $ticket->CreatedAt->format('d M Y') }}
                            </td>

                            <td>

                                <a
                                    href="{{ route('support.ticket.show', $ticket->Id) }}"
                                    class="btn-view"
                                >
                                    View
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="empty-state">

                                <div>

                                    <h3>No Assigned Tickets</h3>

                                    <p>
                                        You currently have no active tickets assigned.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
