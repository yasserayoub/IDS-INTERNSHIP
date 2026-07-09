@extends('layouts.app')

@section('title', 'Notifications | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
@endsection

@section('page-title', 'Notifications')

@section(
    'page-description',
    'Stay updated on ticket assignments, comments, escalations, and status changes.'
)

@section('notifications-active', 'active')


@section('content')

<div class="notifications-toolbar">

    <div class="notification-tabs">
        <button class="tab-button active">All</button>
        <button class="tab-button">Unread</button>
    </div>

    <button class="mark-read-button">
        Mark all as read
    </button>

</div>


<section class="notifications-card">

    <div class="notification-item unread">

        <div class="notification-icon">
            🎫
        </div>

        <div class="notification-content">

            <div class="notification-header">
                <h3>New ticket assigned to you</h3>
                <span>5 minutes ago</span>
            </div>

            <p>
                Ticket <strong>#TKT-1012</strong> — Printer not responding
                has been assigned to you.
            </p>

            <a href="#">
                View Ticket
            </a>

        </div>

        <span class="unread-dot"></span>

    </div>


    <div class="notification-item unread">

        <div class="notification-icon">
            💬
        </div>

        <div class="notification-content">

            <div class="notification-header">
                <h3>You were mentioned in a comment</h3>
                <span>20 minutes ago</span>
            </div>

            <p>
                Sarah Ali mentioned you in a comment on
                <strong>#TKT-1008</strong>.
            </p>

            <a href="#">
                View Conversation
            </a>

        </div>

        <span class="unread-dot"></span>

    </div>


    <div class="notification-item">

        <div class="notification-icon">
            🔄
        </div>

        <div class="notification-content">

            <div class="notification-header">
                <h3>Ticket status updated</h3>
                <span>1 hour ago</span>
            </div>

            <p>
                Ticket <strong>#TKT-1004</strong> status changed from
                In Progress to Resolved.
            </p>

            <a href="#">
                View Ticket
            </a>

        </div>

    </div>


    <div class="notification-item">

        <div class="notification-icon">
            ⚠️
        </div>

        <div class="notification-content">

            <div class="notification-header">
                <h3>Ticket escalated</h3>
                <span>3 hours ago</span>
            </div>

            <p>
                Ticket <strong>#TKT-1001</strong> was escalated to the
                IT Manager for additional review.
            </p>

            <a href="#">
                View Escalation
            </a>

        </div>

    </div>


    <div class="notification-item">

        <div class="notification-icon">
            ✅
        </div>

        <div class="notification-content">

            <div class="notification-header">
                <h3>Ticket resolved</h3>
                <span>Yesterday</span>
            </div>

            <p>
                Your ticket <strong>#TKT-0998</strong> — Outlook login issue
                has been resolved.
            </p>

            <a href="#">
                View Ticket
            </a>

        </div>

    </div>

</section>

@endsection
