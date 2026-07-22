@extends('layouts.app')

@section('title', 'Create Ticket | IT Help Desk')


@section('page-css')
<link rel="stylesheet" href="{{ asset('css/create-ticket.css') }}">
@endsection

@section('page-title', 'Create Ticket')

@section('page-description', 'Submit a new support request to the IT support team.')

@section('create-ticket-active', 'active')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="create-ticket-container">

    <section class="ticket-form-card">

        <div class="form-card-header">
            <h2>New Support Ticket</h2>
            <p>Provide details about the issue you are experiencing.</p>
        </div>
     @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="Title">Ticket Title</label>

                <input
                    type="text"
                    id="Title"
                    name="Title"
                    placeholder="Example: Unable to connect to VPN"
                >

                <small>
                    Enter a short and clear description of the issue.
                </small>
            </div>

            <div class="form-group">

                <label for="CategoryId">Category</label>

                <select id="CategoryId" name="CategoryId">
                    <option value="">Select Category</option>

                    @foreach($categories as $category)
                        <option value="{{ $category->Id }}">
                            {{ $category->Name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="form-group">

                <label for="PriorityId">Priority</label>

                <select id="PriorityId" name="PriorityId">
                    <option value="">Select Priority</option>

                    @foreach($priorities as $priority)
                        <option value="{{ $priority->Id }}">
                            {{ $priority->Name }}
                        </option>
                    @endforeach

                </select>

            </div>

            <div class="form-group">
                <label for="Description">Issue Description</label>

                <textarea
                    id="Description"
                    name="Description"
                    rows="8"
                    placeholder="Describe the issue in detail. Include any error messages and steps you have already tried."
                ></textarea>

                <small>
                    Provide as much information as possible to help the support team.
                </small>
            </div>

            <div class="form-group">

                <label for="attachments">
                    Attachments
                </label>

                <div class="file-upload-area">

                    <div class="upload-icon">
                        📎
                    </div>

                    <p>
                        Attach screenshots or files related to the issue.
                    </p>

                    <input
                        type="file"
                        id="attachments"
                        name="attachments[]"
                        multiple
                    >

                    <small>
                        PNG, JPG, PDF, DOCX — Maximum 10 MB per file
                    </small>

                </div>

            </div>

            <div class="form-actions">

                <a href="/tickets" class="cancel-button">
                    Cancel
                </a>

                <button type="submit" class="submit-button">
                    Submit Ticket
                </button>

            </div>

        </form>

    </section>

    <aside class="ticket-help-card">

        <h3>Before submitting</h3>

        <p>
            To help the IT support team resolve your issue faster:
        </p>

        <ul>
            <li>Use a clear and specific title.</li>
            <li>Choose the correct issue category.</li>
            <li>Explain when the problem started.</li>
            <li>Include relevant error messages.</li>
            <li>Attach screenshots when useful.</li>
        </ul>

        <div class="help-note">
            <strong>Critical issues</strong>

            <p>
                Use Critical priority only for major outages or issues that prevent
                essential business operations.
            </p>
        </div>

    </aside>

</div>

@endsection
