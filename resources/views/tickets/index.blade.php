@extends('layouts.app')

@section('title', 'Tickets | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/tickets.css') }}">
@endsection

@section('page-title', 'Tickets')

@section('page-description', 'View, search, and manage support tickets.')

@section('tickets-active', 'active')


@section('content')

<div class="tickets-toolbar">

    <div class="ticket-search">
        <input
            type="text"
            placeholder="Search tickets..."
        >
    </div>


    <div class="ticket-filters">

        <select>
            <option>All Statuses</option>
            <option>Open</option>
            <option>In Progress</option>
            <option>Pending</option>
            <option>Resolved</option>
            <option>Closed</option>
        </select>


        <select>
            <option>All Priorities</option>
            <option>Low</option>
            <option>Medium</option>
            <option>High</option>
            <option>Critical</option>
        </select>


        <select>
            <option>All Categories</option>
            <option>Hardware</option>
            <option>Software</option>
            <option>Network</option>
            <option>Email</option>
            <option>Access Request</option>
            <option>Other</option>
        </select>

    </div>

</div>


<section class="panel tickets-panel">

    <div class="panel-header">

        <div>
            <h2>All Tickets</h2>
            <p>Manage and track support requests.</p>
        </div>

        <a href="/tickets/create" class="primary-button">
            + Create Ticket
        </a>

    </div>


    <div class="table-wrapper">

        <table>

            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>


            <tbody>

                <tr>
                    <td>#TKT-1001</td>
                    <td>Unable to connect to VPN</td>
                    <td>Network</td>

                    <td>
                        <span class="badge priority-high">
                            High
                        </span>
                    </td>

                    <td>
                        <span class="badge status-open">
                            Open
                        </span>
                    </td>

                    <td>Ahmad Hassan</td>
                    <td>Jul 9, 2026</td>

                    <td>
                        <a href="/tickets/1001" class="table-action">
    View
</a>
                    </td>
                </tr>


                <tr>
                    <td>#TKT-1002</td>
                    <td>Outlook not syncing</td>
                    <td>Email</td>

                    <td>
                        <span class="badge priority-medium">
                            Medium
                        </span>
                    </td>

                    <td>
                        <span class="badge status-progress">
                            In Progress
                        </span>
                    </td>

                    <td>Sarah Ali</td>
                    <td>Jul 8, 2026</td>

                    <td>
                       <a href="/tickets/1001" class="table-action">
    View
</a>
                    </td>
                </tr>


                <tr>
                    <td>#TKT-1003</td>
                    <td>Laptop screen issue</td>
                    <td>Hardware</td>

                    <td>
                        <span class="badge priority-low">
                            Low
                        </span>
                    </td>

                    <td>
                        <span class="badge status-pending">
                            Pending
                        </span>
                    </td>

                    <td>Unassigned</td>
                    <td>Jul 7, 2026</td>

                    <td>
                        <a href="#" class="table-action">
                            View
                        </a>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</section>

@endsection
