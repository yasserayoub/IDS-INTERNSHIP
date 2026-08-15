@extends('layouts.app')

@section('title', 'My Profile | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('page-title', 'My Profile')

@section(
    'page-description',
    'View your personal information and account details.'
)

@section('content')

<div class="profile-layout">

    {{-- PROFILE SUMMARY --}}
    <aside class="profile-summary-card">

        <div class="profile-avatar">
            {{ strtoupper(substr($user->Name, 0, 2)) }}
        </div>

        <h2>{{ $user->Name }}</h2>

        <span class="profile-role">
            {{ $user->role->Name ?? 'No Role' }}
        </span>

        <p class="profile-email">
            {{ $user->Email }}
        </p>

        <div class="profile-status">

            @if($user->IsActive)
                <span class="status-dot"></span>
                Active Account
            @else
                <span class="status-dot inactive"></span>
                Inactive Account
            @endif

        </div>

    </aside>


    {{-- PROFILE INFORMATION --}}
    <div class="profile-main">

        {{-- PERSONAL INFORMATION --}}
        <section class="profile-card">

            <div class="profile-card-header">

                <div>
                    <h2>Personal Information</h2>

                    <p>
                        Your basic account information.
                    </p>
                </div>

            </div>


            <div class="profile-info-grid">

                <div class="profile-info-item">

                    <span>Full Name</span>

                    <strong>
                        {{ $user->Name }}
                    </strong>

                </div>


                <div class="profile-info-item">

                    <span>Email Address</span>

                    <strong>
                        {{ $user->Email }}
                    </strong>

                </div>


                <div class="profile-info-item">

                    <span>Department</span>

                    <strong>
                        {{ $user->Department ?? 'Not provided' }}
                    </strong>

                </div>


                <div class="profile-info-item">

                    <span>Role</span>

                    <strong>
                        {{ $user->role->Name ?? 'No Role' }}
                    </strong>

                </div>

            </div>

        </section>


        {{-- ACCOUNT INFORMATION --}}
        <section class="profile-card">

            <div class="profile-card-header">

                <div>

                    <h2>Account Information</h2>

                    <p>
                        Account status and registration information.
                    </p>

                </div>

            </div>


            <div class="profile-info-grid">

                <div class="profile-info-item">

                    <span>Account Status</span>

                    @if($user->IsActive)

                        <strong class="active-account">
                            Active
                        </strong>

                    @else

                        <strong>
                            Inactive
                        </strong>

                    @endif

                </div>


                <div class="profile-info-item">

                    <span>Member Since</span>

                    <strong>
                        {{ $user->CreatedAt
                            ? \Carbon\Carbon::parse($user->CreatedAt)->format('F d, Y')
                            : 'Not available'
                        }}
                    </strong>

                </div>

            </div>

        </section>

    </div>

</div>

@endsection
