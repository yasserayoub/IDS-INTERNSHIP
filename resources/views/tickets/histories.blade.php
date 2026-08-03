@extends('layouts.app')

@section('title', 'Ticket Histories')

@section('page-title', 'Ticket Histories')

@section('page-description', 'View ticket history and agent work activity.')

@section('history-active', 'active')

@section('content')

<div class="container">

    <div class="card shadow-sm">

        <div class="card-header">
            <h4 class="mb-0">All Tickets</h4>
        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Reference</th>
                            <th>Title</th>
                            <th>Employee</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned To</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($tickets as $ticket)

                            <tr>

                                <td>
                                    <strong>
                                        {{ $ticket->ReferenceNumber }}
                                    </strong>
                                </td>

                                <td>
                                    {{ $ticket->Title }}
                                </td>

                                <td>
                                    {{ $ticket->creator->Name ?? 'Unknown' }}
                                </td>

                                <td>
                                    {{ $ticket->category->Name ?? '-' }}
                                </td>

                                <td>
                                    {{ $ticket->priority->Name ?? '-' }}
                                </td>

                                <td>
                                    {{ $ticket->status->Name ?? '-' }}
                                </td>

                                <td>
                                    {{ optional($ticket->currentAssignment?->assignedTo)->Name ?? 'Not Assigned' }}
                                </td>

                                <td>
                                    {{ $ticket->CreatedAt->format('d M Y') }}
                                </td>

                                <td>

                                   <a href="{{ route('ticket.history.show', $ticket->Id) }}"
   class="btn btn-primary btn-sm">
    View History
</a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="9" class="text-center py-4">

                                    <strong>No tickets found.</strong>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4">
                {{ $tickets->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
