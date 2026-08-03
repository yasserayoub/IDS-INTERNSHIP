@extends('layouts.app')

@section('title', 'Ticket History')

@section('page-title', 'Ticket History')

@section('page-description', 'View the complete history of this ticket.')

@section('history-active', 'active')

@section('content')

<div class="container">

    {{-- ===================================================== --}}
    {{-- TICKET INFORMATION --}}
    {{-- ===================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-1">
                    {{ $ticket->ReferenceNumber }}
                </h4>

                <small class="text-muted">
                    {{ $ticket->Title }}
                </small>

            </div>


            {{-- STATUS BADGE --}}
            <span class="badge bg-primary">

                {{ $ticket->status->Name ?? 'Unknown' }}

            </span>

        </div>


        <div class="card-body">

            @php

                /*
                |--------------------------------------------------------------------------
                | Agent Work Time
                |--------------------------------------------------------------------------
                */

                $agentWorkTimes = $ticket->getWorkTimeByAgent();

                $totalWorkMinutes = collect($agentWorkTimes)
                    ->sum('minutes');

                $totalHours = floor(
                    $totalWorkMinutes / 60
                );

                $totalRemainingMinutes =
                    $totalWorkMinutes % 60;

            @endphp


            {{-- ================================================= --}}
            {{-- FIRST ROW --}}
            {{-- ================================================= --}}

            <div class="row">

                {{-- CREATED BY --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Created By
                    </small>

                    <strong>
                        {{ $ticket->creator->Name ?? 'Unknown' }}
                    </strong>

                </div>


                {{-- CATEGORY --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Category
                    </small>

                    <strong>
                        {{ $ticket->category->Name ?? '-' }}
                    </strong>

                </div>


                {{-- PRIORITY --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Priority
                    </small>

                    <strong>
                        {{ $ticket->priority->Name ?? '-' }}
                    </strong>

                </div>


                {{-- CURRENTLY ASSIGNED --}}
                <div class="col-md-3 mb-4">

                    <small class="text-muted d-block">
                        Currently Assigned To
                    </small>

                    <strong>

                        {{ optional($ticket->currentAssignment?->assignedTo)->Name ?? 'Not Assigned' }}

                    </strong>

                </div>

            </div>


            {{-- ================================================= --}}
            {{-- SECOND ROW --}}
            {{-- ================================================= --}}

            <div class="row">

                {{-- CREATED DATE --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Created
                    </small>

                    <span>

                        {{ $ticket->CreatedAt->format('d M Y h:i A') }}

                    </span>

                </div>


                {{-- CURRENT STATUS --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Current Status
                    </small>

                    <strong>

                        {{ $ticket->status->Name ?? '-' }}

                    </strong>

                </div>


                {{-- TOTAL AGENT WORK TIME --}}
                <div class="col-md-3">

                    <small class="text-muted d-block">
                        Total Agent Work Time
                    </small>

                    <strong>

                        @if($totalHours > 0)

                            {{ $totalHours }}h
                            {{ $totalRemainingMinutes }}m

                        @else

                            {{ $totalRemainingMinutes }}m

                        @endif

                    </strong>

                </div>


                {{-- EMPTY COLUMN TO KEEP GRID ALIGNED --}}
                <div class="col-md-3">

                </div>

            </div>

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- AGENT WORK TIME --}}
    {{-- ===================================================== --}}

    <div class="card shadow-sm mb-4">

        <div class="card-header">

            <h4 class="mb-0">
                Agent Work Time
            </h4>

        </div>


        <div class="card-body">

            @if(count($agentWorkTimes) > 0)

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        {{-- ===================================== --}}
                        {{-- TABLE HEADER --}}
                        {{-- ===================================== --}}

                        <thead class="table-light">

                            <tr>

                                <th>
                                    Agent
                                </th>

                                <th>
                                    Role
                                </th>

                                <th class="text-center">
                                    Work Time
                                </th>

                            </tr>

                        </thead>


                        {{-- ===================================== --}}
                        {{-- TABLE BODY --}}
                        {{-- ===================================== --}}

                        <tbody>

                            @foreach($agentWorkTimes as $work)

                                @php

                                    $workMinutes =
                                        $work['minutes'];

                                    $workHours =
                                        floor(
                                            $workMinutes / 60
                                        );

                                    $remainingMinutes =
                                        $workMinutes % 60;

                                @endphp


                                <tr>

                                    {{-- AGENT NAME --}}
                                    <td>

                                        <strong>

                                            {{ $work['agent']->Name ?? 'Unknown Agent' }}

                                        </strong>

                                    </td>


                                    {{-- AGENT ROLE --}}
                                    <td>

                                        <span class="text-muted">

                                            {{ $work['agent']->role->Name ?? 'IT Support' }}

                                        </span>

                                    </td>


                                    {{-- WORK TIME --}}
                                    <td class="text-center">

                                        <span class="badge bg-primary fs-6">

                                            @if($workHours > 0)

                                                {{ $workHours }}h
                                                {{ $remainingMinutes }}m

                                            @else

                                                {{ $remainingMinutes }}m

                                            @endif

                                        </span>

                                    </td>

                                </tr>

                            @endforeach


                            {{-- ================================= --}}
                            {{-- TOTAL --}}
                            {{-- ================================= --}}

                            <tr class="table-light">

                                <td colspan="2">

                                    <strong>
                                        Total Work Time
                                    </strong>

                                    <br>

                                    <small class="text-muted">

                                        Combined work time from all agents

                                    </small>

                                </td>


                                <td class="text-center">

                                    <strong class="fs-5">

                                        @if($totalHours > 0)

                                            {{ $totalHours }}h
                                            {{ $totalRemainingMinutes }}m

                                        @else

                                            {{ $totalRemainingMinutes }}m

                                        @endif

                                    </strong>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


            @else

                {{-- ============================================= --}}
                {{-- NO WORK TIME --}}
                {{-- ============================================= --}}

                <div class="text-center py-4">

                    <h5>
                        No Agent Work Recorded
                    </h5>

                    <p class="text-muted mb-0">

                        No agent work time has been recorded
                        for this ticket yet.

                    </p>

                </div>

            @endif

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- HISTORY TIMELINE --}}
    {{-- ===================================================== --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h4 class="mb-0">
                History Timeline
            </h4>

        </div>


        <div class="card-body">

            @forelse($histories as $history)

                <div class="border-bottom pb-3 mb-3">

                    <div class="row">

                        {{-- ===================================== --}}
                        {{-- CHANGE INFORMATION --}}
                        {{-- ===================================== --}}

                        <div class="col-md-8">

                            <h6 class="fw-bold">

                                {{ $history->FieldName }} Changed

                            </h6>


                            <p class="mb-2">

                                <span class="text-muted">
                                    From:
                                </span>


                                <strong>

                                    {{ $history->OldValue ?? 'None' }}

                                </strong>


                                <span class="mx-2">

                                    →

                                </span>


                                <span class="text-muted">
                                    To:
                                </span>


                                <strong>

                                    {{ $history->NewValue ?? 'None' }}

                                </strong>

                            </p>


                            <small class="text-muted">

                                Changed by:

                                <strong>

                                    {{ $history->changedBy->Name ?? 'System' }}

                                </strong>

                            </small>

                        </div>


                        {{-- ===================================== --}}
                        {{-- CHANGE DATE --}}
                        {{-- ===================================== --}}

                        <div class="col-md-4 text-end">

                            <small class="text-muted">

                                {{ $history->ChangedAt->format('d M Y') }}

                                <br>

                                {{ $history->ChangedAt->format('h:i A') }}

                            </small>

                        </div>

                    </div>

                </div>


            @empty

                {{-- ============================================= --}}
                {{-- NO HISTORY --}}
                {{-- ============================================= --}}

                <div class="text-center py-5">

                    <h5>
                        No History Found
                    </h5>

                    <p class="text-muted mb-0">

                        No changes have been recorded for this ticket yet.

                    </p>

                </div>

            @endforelse

        </div>

    </div>



    {{-- ===================================================== --}}
    {{-- BACK BUTTON --}}
    {{-- ===================================================== --}}

    <div class="mt-4 mb-4">

        <a
            href="{{ route('ticket.histories') }}"
            class="btn btn-secondary"
        >

            Back to Ticket Histories

        </a>

    </div>

</div>

@endsection
