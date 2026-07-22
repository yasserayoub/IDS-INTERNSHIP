@extends('layouts.app')

@section('title', 'My Tickets')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/employeedashboard.css') }}">
@endsection

@section('page-title', 'My Tickets')

@section('page-description', 'View and manage the support requests you have submitted.')

@section('my-tickets-active', 'active')

@section('content')

<div class="ticket-filters">

    <input
        type="text"
        placeholder="Search my tickets...">

    <div class="filter-group">

        <select>
            <option>All Statuses</option>
            <option>Open</option>
            <option>In Progress</option>
            <option>Pending</option>
            <option>Resolved</option>
        </select>

        <select>
            <option>All Priorities</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            <option>Critical</option>
        </select>

    </div>

</div>


<div class="tickets-card">

    <div class="tickets-header">

        <div>
            <h2>My Tickets</h2>
            <p>Support requests you have submitted.</p>
        </div>

        <a href="/tickets/create" class="create-ticket-button">
            + Create Ticket
        </a>

    </div>

    <table class="tickets-table">

        <thead>

            <tr>
                <th>Ticket</th>
                <th>Title</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>

        </thead>

        <tbody>




@foreach ($tickets as $ticket)

<tr>

    <td>{{ $ticket->ReferenceNumber }}</td>

    <td>{{ $ticket->Title }}</td>

    <td>{{ $ticket->category->Name }}</td>

    <td>
        <span class="priority-{{ strtolower($ticket->priority->Name) }}">
            {{ $ticket->priority->Name }}
        </span>
    </td>

    <td>
            {{ $ticket->status->Name }}
        </span>
    </td>

    <td>{{ $ticket->CreatedAt->format('M d, Y') }}</td>

    <td class="action-buttons">

        <a href="{{ route('employee.tickets.show', $ticket->Id) }}" class="view-btn">
            View
        </a>

        <a href="{{ route('employee.tickets.edit', $ticket->Id) }}" class="edit-btn">
            Edit
        </a>

        <form action="{{ route('employee.tickets.destroy', $ticket->Id) }}" method="POST" class="delete-form">

            @csrf
            @method('DELETE')

            <button type="submit" class="delete-btn">
                Delete
            </button>

        </form>

    </td>

</tr>

@endforeach





        </tbody>

    </table>

</div>

@endsection
