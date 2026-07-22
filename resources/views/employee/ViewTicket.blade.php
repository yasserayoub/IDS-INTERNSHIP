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
            <h2>{{ $ticket->ReferenceNumber }}</h2>
            <p>Support Request Details</p>
        </div>

        <span class="status-open">
            {{ $ticket->status->Name }}
        </span>

    </div>

    <div class="ticket-grid">

        <div class="ticket-item">

            <label>Title</label>

            <p>{{ $ticket->Title }}</p>

        </div>

        <div class="ticket-item">

            <label>Category</label>

            <p>{{ $ticket->category->Name }}</p>

        </div>

        <div class="ticket-item">

            <label>Priority</label>

            <span class="priority-high">
                {{ $ticket->priority->Name }}
            </span>

        </div>

        <div class="ticket-item">

            <label>Created</label>

            <p>{{ $ticket->CreatedAt->format('M j, Y') }}</p>

        </div>

        <div class="ticket-item">

            <label>Last Updated</label>

            <p>{{ $ticket->UpdatedAt->format('M j, Y') }}</p>

        </div>

        <div class="ticket-item">

            <label>Assigned To</label>

            {{-- <p>{{ $ticket->assignedTo->Name }}</p> --}}

        </div>

    </div>


    <div class="ticket-section">

        <h3>Description</h3>

        <p>
          {{$ticket->Description}}
        </p>

    </div>


    <div class="ticket-section">

        <h3>Attachments</h3>

       <ul class="attachment-list">

    @foreach ($ticket->attachments as $attachment)

        <li>
            <span>📎 {{ $attachment->OriginalFileName }}</span>

            <a class="btndownload" href="{{ route('employee.tickets.downloadAttachment', ['id' => $attachment->Id]) }}">
                Download
            </a>
        </li>

    @endforeach

</ul>

    </div>


    <div class="ticket-actions">

        <a href="/employee/dashboard" class="btn-secondary">
            Back
        </a>

        <a href="{{ route('employee.tickets.edit', ['id' => $ticket->Id]) }}" class="btn-warning">
            Edit
        </a>

        <form action="{{ route('employee.tickets.destroy', ['id' => $ticket->Id]) }}" method="POST">

            @csrf
            @method('DELETE')

            <button type="submit" class="btn-danger">
                Delete
            </button>

        </form>

    </div>

</div>

@endsection
