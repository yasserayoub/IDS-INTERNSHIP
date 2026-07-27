@extends('layouts.app')

@section('title', 'Tickets | IT Help Desk')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/tickets.css') }}">
@endsection

@section('page-title', 'Tickets')

@section('page-description', 'View, search, and manage support tickets.')

@section('tickets-active', 'active')

@section('content')

<form method="GET">

    <div class="tickets-toolbar">

        <div class="ticket-search">
            <input
                type="text"
                name="search"
                placeholder="Search tickets..."
                value="{{ request('search') }}">
        </div>

        <div class="ticket-filters">

            <input
                type="date"
                name="from_date"
                value="{{ request('from_date') }}">

            <input
                type="date"
                name="to_date"
                value="{{ request('to_date') }}">

            <!-- Status Filter -->
            <select name="status">
                <option value="">All Statuses</option>

                @foreach ($statuses as $status)
                    <option
                        value="{{ $status->Id }}"
                        {{ request('status') == $status->Id ? 'selected' : '' }}>
                        {{ $status->Name }}
                    </option>
                @endforeach
            </select>

            <!-- Priority Filter -->
            <select name="priority">
                <option value="">All Priorities</option>
                   @foreach ($priorties as $priority)
                    <option
                        value="{{ $priority->Id }}"
                        {{ request('priority') == $priority->Id ? 'selected' : '' }}>
                        {{ $priority->Name }}
                    </option>
                @endforeach
            </select>
            </select>


           <select name="category">
                <option value="">All Categories</option>
                 @foreach ($categories as $category)
                    <option
                        value="{{ $category->Id }}"
                        {{ request('category') == $category->Id ? 'selected' : '' }}>
                        {{ $category->Name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="primary-button">
                Filter
            </button>

        </div>

    </div>

</form>

<section class="panel tickets-panel">

    <div class="panel-header">

        <div>
            <h2>All Tickets</h2>
            <p>Manage and track support requests.</p>
        </div>

        <a href="/tickets/create" class="primary-button">
            + Create Ticket
        </a>

    </div>

    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

            @foreach($tickets as $ticket)

                <tr>

                    <td>{{ $ticket->ReferenceNumber }}</td>

                    <td>{{ $ticket->Title }}</td>

                    <td>{{ $ticket->category->Name }}</td>

                    <td>
                        <span class="badge priority-{{ strtolower($ticket->priority->Name) }}">
                            {{ $ticket->priority->Name }}
                        </span>
                    </td>

                    <td>
                        <span class="badge
                            @if($ticket->status->Name == 'Open')
                                status-open
                            @elseif($ticket->status->Name == 'In Progress')
                                status-progress
                            @elseif($ticket->status->Name == 'Pending')
                                status-pending
                            @elseif($ticket->status->Name == 'Resolved')
                                status-resolved
                            @elseif($ticket->status->Name == 'Closed')
                                status-closed
                            @endif">
                            {{ $ticket->status->Name }}
                        </span>
                    </td>

                    <td>{{ $ticket->creator->Name }}</td>

                    <td>{{ $ticket->CreatedAt->format('M j, Y') }}</td>

                    <td>
                        <a href="#" class="table-action">
                            View
                        </a>
                    </td>

                </tr>

            @endforeach

            </tbody>

        </table>

    </div>

</section>

@endsection
