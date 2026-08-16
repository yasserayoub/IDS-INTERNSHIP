@extends('layouts.app')

@section('title', 'Ticket Details | IT Help Desk')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/ticket-details.css') }}">
@endsection

@section('page-title', 'Ticket #'.$ticket->ReferenceNumber)

@section(
    'page-description',
    'View ticket details, conversation, attachments, and workflow information.'
)

@section('tickets-active', 'active')

@section('content')

<div class="ticket-details-grid">

    <div class="ticket-main-column">

        <section class="details-card">

            <div class="ticket-title-header">

                <div>

                    <h2>{{ $ticket->Title }}</h2>

                    <p>
                        Submitted by {{ $ticket->creator->Name }}
                        on {{ $ticket->CreatedAt->format('F j, Y') }}
                    </p>

                </div>

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

            </div>


            <div class="ticket-meta-grid">

                <div class="meta-item">

                    <span>Category</span>

                    <strong>
                        {{ $ticket->category->Name }}
                    </strong>

                </div>


                <div class="meta-item">

                    <span>Priority</span>

                    <strong class="priority-text-{{ strtolower($ticket->priority->Name) }}">
                        {{ $ticket->priority->Name }}
                    </strong>

                </div>


                <div class="meta-item">

                    <span>Assigned Agent</span>

                    <strong>

                        @if($ticket->AssignedAgentId)

                            {{ optional($itSupports->firstWhere('Id', $ticket->AssignedAgentId))->Name }}

                        @else

                            Not Assigned

                        @endif

                    </strong>

                </div>


                <div class="meta-item">

                    <span>Created</span>

                    <strong>

                        {{ $ticket->CreatedAt->format('M j, Y') }}

                    </strong>

                </div>

            </div>


            <div class="ticket-description">

                <h3>Issue Description</h3>

                <p>
                    {{ $ticket->Description }}
                </p>

            </div>


          <div class="ticket-attachments">

    <h3>Attachments</h3>

    @forelse($ticket->attachments as $attachment)

        <div class="attachment-item">

            <span>
                📎 {{ $attachment->OriginalFileName }}
            </span>

            <a
                href="{{ route('employee.tickets.downloadAttachment', $attachment->Id) }}"
                class="btndownload"
            >
                Download
            </a>

        </div>

    @empty

        <p>No attachments uploaded.</p>

    @endforelse

</div>

        </section>


        <section class="details-card conversation-card">

            <div class="section-heading">

                <h2>Conversation</h2>

                <p>
                    Ticket comments and support responses.
                </p>

            </div>



            @forelse($ticket->comments as $comment)

    <div class="comment">

        <div class="comment-avatar">
            {{ strtoupper(substr($comment->user->Name, 0, 2)) }}
        </div>

        <div class="comment-content">

            <div class="comment-header">

                <div>
                    <strong>{{ $comment->user->Name }}</strong>
                    <span>{{ $comment->user->role->Name }}</span>
                </div>

                <time>{{ $comment->CreatedAt->format('M j, Y g:i A') }}</time>

            </div>

            <p>{{ $comment->Content }}</p>

        </div>

    </div>

@empty

    <p>No comments yet.</p>

@endforelse

<form action="{{ route('manager.ticket.comment', $ticket->Id) }}" method="POST" class="reply-box">

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
            <input type="checkbox" name="IsInternal" value="1">
            Internal note
        </label>

        <button type="submit">
            Add Comment
        </button>

    </div>

</form>

        </section>

    </div>


    <aside class="ticket-side-column">

        <section class="side-card">

           <section class="side-card">

    <h3>Ticket Workflow</h3>

    <form action="{{ route('manager.ticket.assign', $ticket->Id) }}" method="POST">

        @csrf


        <div class="workflow-group">

            <label>Status</label>

            <select name="StatusId">

    <option value="1" {{ $ticket->StatusId == 1 ? 'selected' : '' }}>
        Open
    </option>

    <option value="2" {{ $ticket->StatusId == 2 ? 'selected' : '' }}>
        In Progress
    </option>

    <option value="3" {{ $ticket->StatusId == 3 ? 'selected' : '' }}>
        Resolved
    </option>

    <option value="4" {{ $ticket->StatusId == 4 ? 'selected' : '' }}>
        Closed
    </option>

    <option value="5" {{ $ticket->StatusId == 5 ? 'selected' : '' }}>
        Reopened
    </option>

    <option value="6" {{ $ticket->StatusId == 6 ? 'selected' : '' }}>
        Cancelled
    </option>

    <option value="7" {{ $ticket->StatusId == 7 ? 'selected' : '' }}>
        Pending
    </option>

</select>

            </select>

        </div>

        <div class="workflow-group">

            <label>Assigned Agent</label>

            <select name="AssignedToUserId">

                <option value="">Select IT Support</option>

                @foreach($itSupports as $support)

    <option
        value="{{ $support->Id }}"
         {{ optional($ticket->currentAssignment)->AssignedToUserId == $support->Id ? 'selected' : '' }}>   {{--roo7 ala table ticket assignment -> assigntouserid iza ken howe zeto mtl it agent support id 3malo select la n3rf hyde ticket la meen --}}

        {{ $support->Name }}
        ({{ $support->active_tickets_count }} Active Tickets)

    </option>

@endforeach

            </select>

        </div>

        <button type="submit" class="update-workflow-button">
            Update Ticket
        </button>

    </form>

</section>


        <section class="side-card">

            <h3>Escalation</h3>

            <p class="side-description">
                Escalate this ticket when additional expertise or management
                attention is required.
            </p>

            <label for="escalation-user">
                Escalate To
            </label>

            <select id="escalation-user">
                <option>Select recipient</option>
                <option>Senior IT Support</option>
                <option>IT Manager</option>
            </select>

            <label for="escalation-reason">
                Reason
            </label>

            <textarea
                id="escalation-reason"
                rows="4"
                placeholder="Enter escalation reason..."
            ></textarea>

            <button class="escalate-button">
                Escalate Ticket
            </button>

        </section>


        <section class="side-card">

            <h3>Activity History</h3>

            <div class="history-item">

                <span class="history-dot"></span>

                <div>

                    <strong>Ticket created</strong>

                    <p>
                        {{ $ticket->creator->Name }} ·
                        {{ $ticket->CreatedAt->format('g:i A') }}
                    </p>

                </div>

            </div>

        </section>

    </aside>

</div>

@endsection
