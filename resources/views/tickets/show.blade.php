@extends('layouts.app')

@section('title', 'Ticket Details | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/ticket-details.css') }}">
@endsection

@section('page-title', 'Ticket #TKT-1001')

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
                    <h2>Unable to connect to VPN</h2>
                    <p>
                        Submitted by Michael Brown on July 9, 2026
                    </p>
                </div>

                <span class="badge status-open">
                    Open
                </span>

            </div>


            <div class="ticket-meta-grid">

                <div class="meta-item">
                    <span>Category</span>
                    <strong>Network</strong>
                </div>

                <div class="meta-item">
                    <span>Priority</span>
                    <strong class="priority-text-high">High</strong>
                </div>

                <div class="meta-item">
                    <span>Assigned Agent</span>
                    <strong>Ahmad Hassan</strong>
                </div>

                <div class="meta-item">
                    <span>Created</span>
                    <strong>Jul 9, 2026</strong>
                </div>

            </div>


            <div class="ticket-description">

                <h3>Issue Description</h3>

                <p>
                    I am unable to connect to the company VPN from my laptop.
                    The connection stops after entering my credentials and displays
                    a connection timeout message.
                </p>

                <p>
                    I restarted the laptop and internet router, but the problem
                    still continues.
                </p>

            </div>


            <div class="ticket-attachments">

                <h3>Attachments</h3>

                <div class="attachment-item">
                    <span>📎 vpn-error-screenshot.png</span>
                    <a href="#">View</a>
                </div>

            </div>

        </section>


        <section class="details-card conversation-card">

            <div class="section-heading">
                <h2>Conversation</h2>
                <p>Ticket comments and support responses.</p>
            </div>


            <div class="comment">

                <div class="comment-avatar">
                    MB
                </div>

                <div class="comment-content">

                    <div class="comment-header">
                        <div>
                            <strong>Michael Brown</strong>
                            <span>Employee</span>
                        </div>

                        <time>10:15 AM</time>
                    </div>

                    <p>
                        The VPN problem started this morning. Other websites and
                        internet services are working normally.
                    </p>

                </div>

            </div>


            <div class="comment">

                <div class="comment-avatar">
                    AH
                </div>

                <div class="comment-content">

                    <div class="comment-header">
                        <div>
                            <strong>Ahmad Hassan</strong>
                            <span>IT Support Agent</span>
                        </div>

                        <time>10:32 AM</time>
                    </div>

                    <p>
                        Please confirm whether you recently changed your company
                        password. I will also check the VPN account status.
                    </p>

                </div>

            </div>


            <div class="reply-box">

                <label for="reply">
                    Add Comment
                </label>

                <textarea
                    id="reply"
                    rows="5"
                    placeholder="Write a comment or reply..."
                ></textarea>

                <div class="reply-actions">

                    <label class="internal-note-option">
                        <input type="checkbox">
                        Internal note
                    </label>

                    <button type="button">
                        Add Comment
                    </button>

                </div>

            </div>

        </section>

    </div>


    <aside class="ticket-side-column">

        <section class="side-card">

            <h3>Ticket Workflow</h3>

            <div class="workflow-group">

                <label>Status</label>

                <select>
                    <option>Open</option>
                    <option>In Progress</option>
                    <option>Pending</option>
                    <option>Resolved</option>
                    <option>Closed</option>
                </select>

            </div>


            <div class="workflow-group">

                <label>Assigned Agent</label>

                <select>
                    <option>Ahmad Hassan</option>
                    <option>Sarah Ali</option>
                    <option>Omar Khalil</option>
                </select>

            </div>


            <button class="update-workflow-button">
                Update Ticket
            </button>

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
                    <p>Michael Brown · 10:05 AM</p>
                </div>
            </div>


            <div class="history-item">
                <span class="history-dot"></span>

                <div>
                    <strong>Assigned to Ahmad Hassan</strong>
                    <p>Administrator · 10:20 AM</p>
                </div>
            </div>


            <div class="history-item">
                <span class="history-dot"></span>

                <div>
                    <strong>Status changed to Open</strong>
                    <p>Ahmad Hassan · 10:25 AM</p>
                </div>
            </div>

        </section>

    </aside>

</div>

@endsection
