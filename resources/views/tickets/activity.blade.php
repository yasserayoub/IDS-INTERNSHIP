@extends('layouts.app')

@section('title', 'Activity Log')

@section('page-title', 'Activity Log')

@section('page-description', 'View all system activities.')

@section('activity-active', 'active')

@section('content')

<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Activity Log</h2>
            <p class="text-muted">
                View all activities performed by users in the system.
            </p>
        </div>
    </div>

    <div class="card shadow-sm">

        <div class="card-header">
            <h5 class="mb-0">System Activity Log</h5>
        </div>

        <div class="card-body">

            @forelse($activityLogs as $log)

                <div class="border-bottom pb-3 mb-3">

                    <div class="row">

                        <div class="col-md-8">

                            <h6 class="fw-bold mb-1">
                                {{ $log->Action }}
                            </h6>

                            <p class="mb-1">
                                {{ $log->Description }}
                            </p>

                            <small class="text-muted">
                                Performed by:
                                {{ $log->user->Name ?? 'System' }}
                            </small>

                        </div>

                        <div class="col-md-4 text-end">

                            <small class="text-muted">
                                {{ $log->CreatedAt->format('d M Y') }}
                                <br>
                                {{ $log->CreatedAt->format('h:i A') }}
                            </small>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-4">
                    <h5>No Activity Found</h5>
                    <p class="text-muted">
                        No activity has been recorded yet.
                    </p>
                </div>

            @endforelse

            <div class="mt-3">
                {{ $activityLogs->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
