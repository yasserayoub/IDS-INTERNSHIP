@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('page-css')
<link rel="stylesheet" href="{{ asset('css/editTicket.css') }}">
@endsection

@section('page-title', 'Edit Ticket')

@section('page-description', 'Update your support request.')

@section('my-tickets-active', 'active')

@section('content')

<div class="ticket-card">

    <h2>Edit Support Ticket</h2>
    <p>Update the information below before submitting your changes.</p>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Hidden delete forms --}}
    @foreach($ticket->attachments as $attachment)

        <form id="deleteAttachment{{ $attachment->Id }}"
              action="{{ route('employee.tickets.deleteAttachment', $attachment->Id) }}"
              method="POST"
              style="display:none;">

            @csrf
            @method('DELETE')

        </form>

    @endforeach

    {{-- Main Update Form --}}
    <form action="{{ route('employee.tickets.update', $ticket->Id) }}"
          method="POST"
          enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label for="Title">Ticket Title</label>

                <input
                    type="text"
                    id="Title"
                    name="Title"
                    value="{{ old('Title', $ticket->Title) }}"
                    required>

            </div>

            <div class="form-group">

                <label for="CategoryId">Category</label>

                <select id="CategoryId" name="CategoryId" required>

                    @foreach($categories as $category)

                        <option
                            value="{{ $category->Id }}"
                            {{ old('CategoryId', $ticket->CategoryId) == $category->Id ? 'selected' : '' }}>

                            {{ $category->Name }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label for="PriorityId">Priority</label>

                <select id="PriorityId" name="PriorityId" required>

                    @foreach($priorities as $priority)

                        <option
                            value="{{ $priority->Id }}"
                            {{ old('PriorityId', $ticket->PriorityId) == $priority->Id ? 'selected' : '' }}>

                            {{ $priority->Name }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

        <div class="form-group">

            <label for="Description">Description</label>

            <textarea
                id="Description"
                name="Description"
                rows="8"
                required>{{ old('Description', $ticket->Description) }}</textarea>

        </div>

        {{-- Current Attachments --}}
        <div class="form-group">

            <label>Current Attachments</label>

            @if($ticket->attachments->count())

                <ul class="attachment-list">

                    @foreach($ticket->attachments as $attachment)

                        <li>

                            <span class="attachment-name">
                                📎 {{ $attachment->OriginalFileName }}
                            </span>

                            <div class="attachment-actions">

                                <a href="{{ route('employee.tickets.downloadAttachment', $attachment->Id) }}"
                                   class="btndownload">
                                    Download
                                </a>

                                <button
                                    type="submit"
                                    form="deleteAttachment{{ $attachment->Id }}"
                                    class="btn-danger"
                                    onclick="return confirm('Delete this attachment?')">

                                    Delete

                                </button>

                            </div>

                        </li>

                    @endforeach

                </ul>

            @else

                <p>No attachments uploaded.</p>

            @endif

        </div>

        {{-- Upload --}}
        <div class="form-group">

            <label for="Attachment">Upload New Attachment</label>

            <input
                type="file"
                id="Attachment"
                name="Attachment">

            <small>
                Upload another attachment if needed.
            </small>

        </div>

        <div class="ticket-actions">

            <a href="{{ route('employee.tickets.show', $ticket->Id) }}"
               class="btn-secondary">

                Cancel

            </a>

            <button type="submit" class="btn-primary">

                Save Changes

            </button>

        </div>

    </form>

</div>

@endsection
