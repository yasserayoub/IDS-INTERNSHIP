@extends('layouts.app')

@section('title', 'Assigned Ticket')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/Itagentviewticket.css') }}">
@endsection

@section('page-title', 'Assigned Ticket')

@section('page-description', 'View and manage your assigned ticket.')

@section('my-tickets-active', 'active')

@section('content')

<div class="ticket-card">

    <div class="ticket-header">

        <div>

            <h2>{{ $ticket->ReferenceNumber }}</h2>

            <p>Assigned Support Ticket</p>

        </div>

        <span class="status status-{{ strtolower(str_replace(' ','-',$ticket->status->Name)) }}">
            {{ $ticket->status->Name }}
        </span>

    </div>

    <div class="ticket-grid">

        <div class="ticket-item">
            <label>Title</label>
            <p>{{ $ticket->Title }}</p>
        </div>

        <div class="ticket-item">
            <label>Employee</label>
            <p>{{ $ticket->creator->Name }}</p>
        </div>

        <div class="ticket-item">
            <label>Category</label>
            <p>{{ $ticket->category->Name }}</p>
        </div>

        <div class="ticket-item">
            <label>Priority</label>

            <span class="priority priority-{{ strtolower($ticket->priority->Name) }}">
                {{ $ticket->priority->Name }}
            </span>

        </div>

        <div class="ticket-item">
            <label>Assigned To</label>

            <p>
                {{ optional($ticket->currentAssignment->assignedTo)->Name ?? 'Not Assigned' }}
            </p>

        </div>

        <div class="ticket-item">
            <label>Created</label>
            <p>{{ $ticket->CreatedAt->format('M j, Y') }}</p>
        </div>

        <div class="ticket-item">
            <label>Last Updated</label>
            <p>{{ $ticket->UpdatedAt->format('M j, Y') }}</p>
        </div>

    </div>

    <div class="ticket-section">

        <h3>Description</h3>

        <p>
            {{ $ticket->Description }}
        </p>

    </div>

    <div class="ticket-section">

        <h3>Workflow</h3>

        <form action="{{ route('support.ticket.status', $ticket->Id) }}" method="POST">

            @csrf

            <div class="workflow-group">

                <label>Status</label>

                <select name="StatusId">

                    @foreach($statuses as $status)

                        <option
                            value="{{ $status->Id }}"
                            {{ $ticket->StatusId == $status->Id ? 'selected' : '' }}>

                            {{ $status->Name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <button type="submit" class="primary-button">

                Update Status

            </button>

        </form>

    </div>

    <div class="ticket-section">

        <h3>Attachments</h3>

        <ul class="attachment-list">

            @forelse($ticket->attachments as $attachment)

                <li>

                    <span>
                        📎 {{ $attachment->OriginalFileName }}
                    </span>

                    <a
                        class="btndownload"
                        href="{{ route('employee.tickets.downloadAttachment', $attachment->Id) }}">

                        Download

                    </a>

                </li>

            @empty

                <li>No attachments uploaded.</li>

            @endforelse

        </ul>

    </div>
    <div class="ticket-section">

    <h3>Conversation</h3>

    @forelse($ticket->comments as $comment)

        @if(!$comment->IsInternal || Auth::user()->role->Name != 'Employee')

            <div class="comment">

                <div class="comment-avatar">
                    {{ strtoupper(substr($comment->user->Name, 0, 2)) }}
                </div>

                <div class="comment-content">

                    <div class="comment-header">

                        <div>

                            <strong>{{ $comment->user->Name }}</strong>

                            <span>{{ $comment->user->role->Name }}</span>

                            @if($comment->IsInternal)
                                <span class="internal-badge">Internal Note</span>
                            @endif

                        </div>

                        <time>
                            {{ $comment->CreatedAt->format('M j, Y g:i A') }}
                        </time>

                    </div>

                    <p>{{ $comment->Content }}</p>

                </div>

            </div>

        @endif

    @empty

        <p>No comments yet.</p>

    @endforelse

</div>

<form action="{{ route('support.ticket.comment', $ticket->Id) }}" method="POST" class="reply-box">

    @csrf

    <label for="Content">
        Add Comment
    </label>

    <textarea
        name="Content"
        id="Content"
        rows="5"
        placeholder="Write a comment or reply..."
        required
    ></textarea>

    <div class="reply-actions">

        <label class="internal-note-option">

            <input
                type="checkbox"
                name="IsInternal"
                value="1"
            >

            Internal Note

        </label>

        <button type="submit">
            Add Comment
        </button>

    </div>

</form>

    <div class="ticket-actions">

        <a href="{{ route('support.tickets') }}" class="btn-secondary">

            Back to My Tickets

        </a>

    </div>

</div>

@endsection
