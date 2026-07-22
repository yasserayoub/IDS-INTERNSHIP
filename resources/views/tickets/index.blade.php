@extends('layouts.app')

@section('title', 'Tickets | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/tickets.css') }}">
@endsection

@section('page-title', 'Tickets')

@section('page-description', 'View, search, and manage support tickets.')

@section('tickets-active', 'active')


@section('content')

<div class="tickets-toolbar">

    <div class="ticket-search">
        <input
            type="text"
            placeholder="Search tickets..."
        >
    </div>


    <div class="ticket-filters">

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


        <select>
            <option>All Categories</option>
            <option>Hardware</option>
            <option>Software</option>
            <option>Network</option>
            <option>Email</option>
            <option>Access Request</option>
            <option>Other</option>
        </select>

    </div>

</div>


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
            @endif">
            {{ $ticket->status->Name }}
        </span>
    </td>

    <td>
        {{ $ticket->creator->Name }}
    </td>

    <td>
        {{ $ticket->CreatedAt->format('M j, Y') }}
    </td>

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
