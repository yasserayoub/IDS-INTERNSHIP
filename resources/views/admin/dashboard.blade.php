@extends('layouts.app')

@section('title', 'Admin Dashboard | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endsection

@section('page-title', 'Admin Dashboard')

@section(
    'page-description',
    'Monitor support activity, ticket workload, and system performance.'
)

@section('dashboard-active', 'active')


@section('content')

<div class="admin-stats">

    <div class="admin-stat-card">
        <div>
            <span>Total Tickets</span>
            <h2>128</h2>
            <p>All support requests</p>
        </div>

        <div class="stat-icon">
            🎫
        </div>
    </div>


    <div class="admin-stat-card">
        <div>
            <span>Open Tickets</span>
            <h2>24</h2>
            <p>Waiting for resolution</p>
        </div>

        <div class="stat-icon">
            📂
        </div>
    </div>


    <div class="admin-stat-card warning-card">
        <div>
            <span>Unassigned</span>
            <h2>7</h2>
            <p>Require agent assignment</p>
        </div>

        <div class="stat-icon">
            👤
        </div>
    </div>


    <div class="admin-stat-card critical-card">
        <div>
            <span>Critical Tickets</span>
            <h2>4</h2>
            <p>Require immediate attention</p>
        </div>

        <div class="stat-icon">
            ⚠️
        </div>
    </div>

</div>


<div class="admin-dashboard-grid">

    <section class="admin-card recent-tickets-card">

        <div class="admin-card-header">

            <div>
                <h2>Recent Tickets</h2>
                <p>Latest support requests submitted by employees.</p>
            </div>

            <a href="/tickets">
                View All
            </a>

        </div>


        <div class="admin-table-wrapper">

            <table class="admin-table">

                <thead>
                    <tr>
                        <th>Ticket</th>
                        <th>Title</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                    </tr>
                </thead>


                <tbody>

                    <tr>
                        <td>#TKT-1012</td>

                        <td>Printer not responding</td>

                        <td>
                            <span class="admin-badge priority-medium">
                                Medium
                            </span>
                        </td>

                        <td>
                            <span class="admin-badge status-open">
                                Open
                            </span>
                        </td>

                        <td>
                            <span class="unassigned-text">
                                Unassigned
                            </span>
                        </td>
                    </tr>


                    <tr>
                        <td>#TKT-1011</td>

                        <td>Main server unavailable</td>

                        <td>
                            <span class="admin-badge priority-critical">
                                Critical
                            </span>
                        </td>

                        <td>
                            <span class="admin-badge status-progress">
                                In Progress
                            </span>
                        </td>

                        <td>Ahmad Hassan</td>
                    </tr>


                    <tr>
                        <td>#TKT-1010</td>

                        <td>Outlook not syncing</td>

                        <td>
                            <span class="admin-badge priority-high">
                                High
                            </span>
                        </td>

                        <td>
                            <span class="admin-badge status-pending">
                                Pending
                            </span>
                        </td>

                        <td>Sarah Ali</td>
                    </tr>


                    <tr>
                        <td>#TKT-1009</td>

                        <td>Software installation request</td>

                        <td>
                            <span class="admin-badge priority-low">
                                Low
                            </span>
                        </td>

                        <td>
                            <span class="admin-badge status-open">
                                Open
                            </span>
                        </td>

                        <td>Omar Khalil</td>
                    </tr>

                </tbody>

            </table>

        </div>

    </section>


    <section class="admin-card quick-actions-card">

        <div class="admin-card-header">

            <div>
                <h2>Quick Actions</h2>
                <p>Common administration tasks.</p>
            </div>

        </div>


        <div class="quick-actions">

            <a href="/tickets" class="quick-action">
                <span>🎫</span>

                <div>
                    <strong>Manage Tickets</strong>
                    <p>Assign and monitor support tickets.</p>
                </div>
            </a>


           <a href="{{route('UserManagementpage')}}" class="quick-action">
                <span>👥</span>

                <div>
                    <strong>Manage Users</strong>
                    <p>View users and manage roles.</p>
                </div>
            </a>


            <a href="/reports" class="quick-action">
                <span>📊</span>

                <div>
                    <strong>View Reports</strong>
                    <p>Review support performance.</p>
                </div>
            </a>


            <a href="#" class="quick-action">
                <span>⚙️</span>

                <div>
                    <strong>System Settings</strong>
                    <p>Configure help desk settings.</p>
                </div>
            </a>

        </div>

    </section>

</div>


<section class="admin-card agent-workload-card">

    <div class="admin-card-header">

        <div>
            <h2>Agent Workload</h2>
            <p>Current ticket distribution across support agents.</p>
        </div>

    </div>


    <div class="workload-grid">

        <div class="agent-workload">

            <div class="agent-info">
                <div class="agent-avatar">
                    AH
                </div>

                <div>
                    <strong>Ahmad Hassan</strong>
                    <span>IT Support Agent</span>
                </div>
            </div>


            <div class="workload-details">
                <span>12 active tickets</span>

                <div class="workload-track">
                    <div class="workload-fill workload-high"></div>
                </div>
            </div>

        </div>


        <div class="agent-workload">

            <div class="agent-info">
                <div class="agent-avatar">
                    SA
                </div>

                <div>
                    <strong>Sarah Ali</strong>
                    <span>IT Support Agent</span>
                </div>
            </div>


            <div class="workload-details">
                <span>8 active tickets</span>

                <div class="workload-track">
                    <div class="workload-fill workload-medium"></div>
                </div>
            </div>

        </div>


        <div class="agent-workload">

            <div class="agent-info">
                <div class="agent-avatar">
                    OK
                </div>

                <div>
                    <strong>Omar Khalil</strong>
                    <span>IT Support Agent</span>
                </div>
            </div>


            <div class="workload-details">
                <span>5 active tickets</span>

                <div class="workload-track">
                    <div class="workload-fill workload-low"></div>
                </div>
            </div>

        </div>

    </div>

</section>

@endsection
