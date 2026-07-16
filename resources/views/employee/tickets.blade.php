@extends('layouts.app')

@section('title', 'View Ticket')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/viewticketemployee.css') }}">
@endsection

@section('page-title', 'Ticket Details')

@section('page-description', 'View the details of your support request.')

@section('my-tickets-active', 'active')

@section('content')

<div class="ticket-card">

    <div class="ticket-header">

        <div>
            <h2>#TKT-1001</h2>
            <p>Support Request Details</p>
        </div>

        <span class="status-open">
            Open
        </span>

    </div>

    <div class="ticket-grid">

        <div class="ticket-item">

            <label>Title</label>

            <p>Unable to connect to VPN</p>

        </div>

        <div class="ticket-item">

            <label>Category</label>

            <p>Network</p>

        </div>

        <div class="ticket-item">

            <label>Priority</label>

            <span class="priority-high">
                High
            </span>

        </div>

        <div class="ticket-item">

            <label>Created</label>

            <p>Jul 9, 2026</p>

        </div>

        <div class="ticket-item">

            <label>Last Updated</label>

            <p>Jul 10, 2026</p>

        </div>

        <div class="ticket-item">

            <label>Assigned To</label>

            <p>Ahmad Hassan</p>

        </div>

    </div>


    <div class="ticket-section">

        <h3>Description</h3>

        <p>
            I cannot connect to the company VPN since this morning.
            Every time I try to connect, Windows displays error 809.
            I restarted my computer and internet router but the issue still exists.
        </p>

    </div>


    <div class="ticket-section">

        <h3>Attachments</h3>

        <ul class="attachment-list">

            <li>
                📄 vpn-error.png
            </li>

            <li>
                📄 screenshot.jpg
            </li>

        </ul>

    </div>


    <div class="ticket-actions">

        <a href="/employee/dashboard" class="btn-secondary">
            Back
        </a>

        <a href="{{ route('employee.tickets.edit', ['id' => $ticketId]) }}" class="btn-warning">
            Edit
        </a>

        <form action="{{ route('employee.tickets.destroy', ['id' => $ticketId]) }}" method="POST">

            @csrf
            @method('DELETE')

            <button type="submit" class="btn-danger">
                Delete
            </button>

        </form>

    </div>

</div>

@endsection
