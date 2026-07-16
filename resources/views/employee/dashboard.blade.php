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

            <!-- Ticket 1 -->
            <tr>

                <td>#TKT-1001</td>
                <td>Unable to connect to VPN</td>
                <td>Network</td>

                <td>
                    <span class="priority-high">High</span>
                </td>

                <td>
                    <span class="status-open">Open</span>
                </td>

                <td>Jul 9, 2026</td>

                <td class="action-buttons">

                    <a href="{{ route('employee.tickets.show', 1001) }}" class="view-btn">
                        View
                    </a>

                    <a href="{{ route('employee.tickets.edit', 1001) }}" class="edit-btn">
                        Edit
                    </a>

                    <form action="{{ route('employee.tickets.destroy', 1001) }}" method="POST" class="delete-form">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-btn">
                            Delete
                        </button>

                    </form>

                </td>

            </tr>


            <!-- Ticket 2 -->
            <tr>

                <td>#TKT-1005</td>
                <td>Printer not working</td>
                <td>Hardware</td>

                <td>
                    <span class="priority-low">Low</span>
                </td>

                <td>
                    <span class="status-progress">In Progress</span>
                </td>

                <td>Jul 7, 2026</td>

                <td class="action-buttons">

                    <a href="{{ route('employee.tickets.show', 1005) }}" class="view-btn">
                        View
                    </a>

                </td>

            </tr>


            <!-- Ticket 3 -->
            <tr>

                <td>#TKT-1007</td>
                <td>Outlook synchronization issue</td>
                <td>Email</td>

                <td>
                    <span class="priority-medium">Medium</span>
                </td>

                <td>
                    <span class="status-resolved">Resolved</span>
                </td>

                <td>Jul 2, 2026</td>

                <td class="action-buttons">

                    <a href="/tickets/1007" class="view-btn">
                        View
                    </a>

                </td>

            </tr>


            <!-- Ticket 4 -->
            <tr>

                <td>#TKT-1009</td>
                <td>Email signature issue</td>
                <td>Email</td>

                <td>
                    <span class="priority-low">Low</span>
                </td>

                <td>
                    <span class="status-pending">Pending</span>
                </td>

                <td>Jun 30, 2026</td>

                <td class="action-buttons">

                    <a href="/tickets/1009" class="view-btn">
                        View
                    </a>

                </td>

            </tr>

        </tbody>

    </table>

</div>

@endsection
