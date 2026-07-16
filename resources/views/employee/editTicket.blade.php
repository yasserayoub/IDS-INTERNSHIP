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

    <form action="/employee/tickets/1001" method="POST">

        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">

                <label>Ticket Title</label>

                <input
                    type="text"
                    name="Title"
                    value="Unable to connect to VPN"
                    required>

            </div>

            <div class="form-group">

                <label>Category</label>

                <select name="Category">

                    <option>Hardware</option>
                    <option selected>Network</option>
                    <option>Email</option>
                    <option>Software</option>

                </select>

            </div>

            <div class="form-group">

                <label>Priority</label>

                <select name="Priority">

                    <option>Low</option>
                    <option>Medium</option>
                    <option selected>High</option>
                    <option>Critical</option>

                </select>

            </div>

        </div>

        <div class="form-group">

            <label>Description</label>

            <textarea
                name="Description"
                rows="8"
                required>I cannot connect to the company VPN since this morning. Windows keeps displaying error 809.</textarea>

        </div>

        <div class="form-group">

            <label>Attachments</label>

            <input type="file" name="Attachment">

            <small>
                Current attachment: vpn-error.png
            </small>

        </div>

        <div class="ticket-actions">

            <a href="/employee/tickets/1001" class="btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn-primary">
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection
