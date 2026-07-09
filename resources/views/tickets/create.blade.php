<div>
    <!-- Because you are alive, everything is possible. - Thich Nhat Hanh -->
</div>@extends('layouts.app')

@section('title', 'Create Ticket | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/create-ticket.css') }}">
@endsection

@section('page-title', 'Create Ticket')

@section(
    'page-description',
    'Submit a new support request to the IT support team.'
)

@section('create-ticket-active', 'active')


@section('content')

<div class="create-ticket-container">

    <section class="ticket-form-card">

        <div class="form-card-header">
            <h2>New Support Ticket</h2>
            <p>Provide details about the issue you are experiencing.</p>
        </div>


        <form>

            <div class="form-group">
                <label for="title">Ticket Title</label>

                <input
                    type="text"
                    id="title"
                    name="title"
                    placeholder="Example: Unable to connect to VPN"
                >

                <small>
                    Enter a short and clear description of the issue.
                </small>
            </div>


            <div class="form-row">

                <div class="form-group">
                    <label for="category">Category</label>

                    <select id="category" name="category">
                        <option value="">Select category</option>
                        <option>Hardware</option>
                        <option>Software</option>
                        <option>Network</option>
                        <option>Email</option>
                        <option>Access Request</option>
                        <option>Other</option>
                    </select>
                </div>


                <div class="form-group">
                    <label for="priority">Priority</label>

                    <select id="priority" name="priority">
                        <option value="">Select priority</option>
                        <option>Low</option>
                        <option>Medium</option>
                        <option>High</option>
                        <option>Critical</option>
                    </select>
                </div>

            </div>


            <div class="form-group">
                <label for="description">Issue Description</label>

                <textarea
                    id="description"
                    name="description"
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
