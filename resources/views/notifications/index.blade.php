@extends('layouts.app')

@section('title', 'Notifications')

@section('page-title', 'Notifications')

@section('page-description', 'View your recent notifications.')

@section('notifications-active', 'active')

@section('content')

<div class="card shadow-sm">

    {{-- Header --}}
    <div class="card-header d-flex justify-content-between align-items-center">

        <h4 class="mb-0">
            Notification Center
        </h4>

        <div class="d-flex align-items-center gap-2">

            <span class="badge bg-primary">
                {{ $notifications->total() }} Notifications
            </span>

            <form action="{{ route('notifications.readAll') }}" method="POST">
                @csrf

                <button
                    type="submit"
                    class="btn btn-success btn-sm"
                >
                    Mark All as Read
                </button>

            </form>

        </div>

    </div>

    {{-- Body --}}
    <div class="card-body">

        @forelse($notifications as $notification)

            <div class="border rounded p-3 mb-3 {{ $notification->IsRead ? 'bg-light' : 'border-primary' }}">

                <div class="d-flex justify-content-between align-items-start">

                    <div class="flex-grow-1">

                        <h5 class="mb-1">
                            {{ $notification->Title }}
                        </h5>

                        <p class="mb-2">
                            {{ $notification->Message }}
                        </p>

                        <small class="text-muted">

                            {{ ucfirst(str_replace('_', ' ', $notification->Type)) }}

                            •

                            {{ $notification->CreatedAt->diffForHumans() }}

                        </small>

                    </div>

                    <div class="text-end">

                        @if(!$notification->IsRead)

                            <span class="badge bg-success mb-2">
                                New
                            </span>

                            <br>

                        @endif

                        @php

                            $ticketRoute = null;

                            switch (Auth::user()->role->Name) {

                                case 'Administrator':
                                case 'IT Manager':

                                    $ticketRoute = route(
                                        'manager.ticket.show',
                                        $notification->TicketId
                                    );

                                    break;

                                case 'IT Support':

                                    $ticketRoute = route(
                                        'support.ticket.show',
                                        $notification->TicketId
                                    );

                                    break;

                                case 'Employee':

                                    $ticketRoute = route(
                                        'employee.tickets.show',
                                        $notification->TicketId
                                    );

                                    break;
                            }

                        @endphp

                        @if($notification->ticket)

                            <a
                                href="{{ $ticketRoute }}"
                                class="btn btn-outline-primary btn-sm"
                            >
                                View Ticket
                            </a>

                        @endif

                        @if(!$notification->IsRead)

                            <form
                                action="{{ route('notifications.read', $notification->Id) }}"
                                method="POST"
                                class="mt-2"
                            >
                                @csrf

                                <button
                                    type="submit"
                                    class="btn btn-success btn-sm"
                                >
                                    Mark as Read
                                </button>

                            </form>

                        @endif

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center py-5">

                <h4>No Notifications</h4>

                <p class="text-muted mb-0">
                    You're all caught up!
                </p>

            </div>

        @endforelse

    </div>

    @if($notifications->hasPages())

        <div class="card-footer">

            {{ $notifications->links() }}

        </div>

    @endif

</div>

@endsection
