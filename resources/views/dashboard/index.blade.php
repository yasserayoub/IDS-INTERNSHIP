@extends('layouts.app')


@section('title', 'Dashboard | IT Help Desk')

@section('page-title', 'Dashboard')
@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section(
    'page-description',
    "Welcome back. Here's an overview of support activity."
)

@section('dashboard-active', 'active')


@section('content')


<div class="stats-grid">

    <div class="stat-card">
        <p>Open Tickets</p>
        <h2>24</h2>
    </div>

    <div class="stat-card">
        <p>Pending Tickets</p>
        <h2>8</h2>
    </div>

    <div class="stat-card">
        <p>Resolved Tickets</p>
        <h2>46</h2>
    </div>

    <div class="stat-card">
        <p>Total Tickets</p>
        <h2>78</h2>
    </div>

</div>


<div class="dashboard-panels">

    <section class="panel">

        <div class="panel-header">

            <h2>Recent Tickets</h2>

            <a href="#">
                View All
            </a>

        </div>


        <table>

            <thead>
                <tr>
                    <th>Ticket</th>
                    <th>Title</th>
                    <th>Priority</th>
                    <th>Status</th>
                </tr>
            </thead>


            <tbody>

                <tr>

                    <td>#TKT-1001</td>

                    <td>
                        Unable to connect to VPN
                    </td>

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

                </tr>


                <tr>

                    <td>#TKT-1002</td>

                    <td>
                        Outlook not syncing
                    </td>

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

                </tr>


                <tr>

                    <td>#TKT-1003</td>

                    <td>
                        Laptop screen issue
                    </td>

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

                </tr>

            </tbody>

        </table>

    </section>

</div>


@endsection
