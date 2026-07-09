@extends('layouts.app')

@section('title', 'Reports | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/reports.css') }}">
@endsection

@section('page-title', 'Reports')

@section(
    'page-description',
    'Analyze ticket performance, resolution times, and support activity.'
)

@section('reports-active', 'active')


@section('content')

<div class="reports-toolbar">

    <div class="report-period">
        <label for="period">Report Period</label>

        <select id="period">
            <option>July 2026</option>
            <option>June 2026</option>
            <option>May 2026</option>
            <option>April 2026</option>
        </select>
    </div>

    <button class="export-button">
        Export Report
    </button>

</div>


<div class="report-stats">

    <div class="report-stat-card">
        <span>Total Tickets</span>
        <h2>128</h2>
        <p>+12% from last month</p>
    </div>

    <div class="report-stat-card">
        <span>Resolved Tickets</span>
        <h2>96</h2>
        <p>75% resolution rate</p>
    </div>

    <div class="report-stat-card">
        <span>Average Resolution Time</span>
        <h2>4.2h</h2>
        <p>0.8h faster than last month</p>
    </div>

    <div class="report-stat-card">
        <span>Pending Tickets</span>
        <h2>14</h2>
        <p>11% of total tickets</p>
    </div>

</div>


<div class="reports-grid">

    <!-- Tickets by Category -->

    <section class="report-card">

        <div class="report-card-header">
            <div>
                <h2>Tickets by Category</h2>
                <p>Distribution of support requests.</p>
            </div>
        </div>


        <div class="bar-chart">

            <div class="bar-row">
                <div class="bar-label">
                    <span>Hardware</span>
                    <strong>42</strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill width-100"></div>
                </div>
            </div>


            <div class="bar-row">
                <div class="bar-label">
                    <span>Software</span>
                    <strong>35</strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill width-83"></div>
                </div>
            </div>


            <div class="bar-row">
                <div class="bar-label">
                    <span>Network</span>
                    <strong>28</strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill width-67"></div>
                </div>
            </div>


            <div class="bar-row">
                <div class="bar-label">
                    <span>Email</span>
                    <strong>15</strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill width-36"></div>
                </div>
            </div>


            <div class="bar-row">
                <div class="bar-label">
                    <span>Other</span>
                    <strong>8</strong>
                </div>

                <div class="bar-track">
                    <div class="bar-fill width-20"></div>
                </div>
            </div>

        </div>

    </section>


    <!-- Tickets by Priority -->

    <section class="report-card">

        <div class="report-card-header">
            <div>
                <h2>Tickets by Priority</h2>
                <p>Ticket distribution by urgency level.</p>
            </div>
        </div>


        <div class="priority-report">

            <div class="priority-report-item">
                <div>
                    <span class="priority-dot critical-dot"></span>
                    <span>Critical</span>
                </div>

                <strong>8</strong>
            </div>


            <div class="priority-report-item">
                <div>
                    <span class="priority-dot high-dot"></span>
                    <span>High</span>
                </div>

                <strong>30</strong>
            </div>


            <div class="priority-report-item">
                <div>
                    <span class="priority-dot medium-dot"></span>
                    <span>Medium</span>
                </div>

                <strong>54</strong>
            </div>


            <div class="priority-report-item">
                <div>
                    <span class="priority-dot low-dot"></span>
                    <span>Low</span>
                </div>

                <strong>36</strong>
            </div>

        </div>


        <div class="priority-total">
            <span>Total Tickets</span>
            <strong>128</strong>
        </div>

    </section>

</div>


<!-- Agent Performance -->

<section class="report-card agent-performance-card">

    <div class="report-card-header">

        <div>
            <h2>Agent Performance</h2>
            <p>Ticket resolution performance for IT support agents.</p>
        </div>

    </div>


    <div class="report-table-wrapper">

        <table class="report-table">

            <thead>
                <tr>
                    <th>Agent</th>
                    <th>Assigned</th>
                    <th>Resolved</th>
                    <th>Pending</th>
                    <th>Avg. Resolution</th>
                    <th>Resolution Rate</th>
                </tr>
            </thead>


            <tbody>

                <tr>
                    <td>
                        <div class="agent-cell">
                            <span class="agent-avatar">AH</span>
                            <strong>Ahmad Hassan</strong>
                        </div>
                    </td>

                    <td>42</td>
                    <td>36</td>
                    <td>6</td>
                    <td>3.8h</td>

                    <td>
                        <span class="rate-badge good-rate">
                            86%
                        </span>
                    </td>
                </tr>


                <tr>
                    <td>
                        <div class="agent-cell">
                            <span class="agent-avatar">SA</span>
                            <strong>Sarah Ali</strong>
                        </div>
                    </td>

                    <td>38</td>
                    <td>31</td>
                    <td>7</td>
                    <td>4.1h</td>

                    <td>
                        <span class="rate-badge good-rate">
                            82%
                        </span>
                    </td>
                </tr>


                <tr>
                    <td>
                        <div class="agent-cell">
                            <span class="agent-avatar">OK</span>
                            <strong>yaser Ayoub</strong>
                        </div>
                    </td>

                    <td>35</td>
                    <td>29</td>
                    <td>6</td>
                    <td>4.7h</td>

                    <td>
                        <span class="rate-badge medium-rate">
                            83%
                        </span>
                    </td>
                </tr>

            </tbody>

        </table>

    </div>

</section>

@endsection
