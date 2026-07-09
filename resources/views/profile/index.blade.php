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

    <aside class="profile-summary-card">

        <div class="profile-avatar">
            YA
        </div>

        <h2>Yasser Ayoub</h2>

        <span class="profile-role">
            Administrator
        </span>

        <p class="profile-email">
            yasser.ayoub@company.com
        </p>

        <div class="profile-status">
            <span class="status-dot"></span>
            Active Account
        </div>

        <button class="edit-profile-button">
            Edit Profile
        </button>

    </aside>


    <div class="profile-main">

        <section class="profile-card">

            <div class="profile-card-header">

                <div>
                    <h2>Personal Information</h2>
                    <p>Your basic account information.</p>
                </div>

            </div>


            <div class="profile-info-grid">

                <div class="profile-info-item">
                    <span>Full Name</span>
                    <strong>Yasser Ayoub</strong>
                </div>

                <div class="profile-info-item">
                    <span>Email Address</span>
                    <strong>yasser.ayoub@company.com</strong>
                </div>

                <div class="profile-info-item">
                    <span>Phone Number</span>
                    <strong>+961 70 123 456</strong>
                </div>

                <div class="profile-info-item">
                    <span>Department</span>
                    <strong>Information Technology</strong>
                </div>

                <div class="profile-info-item">
                    <span>Role</span>
                    <strong>Administrator</strong>
                </div>

                <div class="profile-info-item">
                    <span>Employee ID</span>
                    <strong>EMP-001</strong>
                </div>

            </div>

        </section>


        <section class="profile-card">

            <div class="profile-card-header">

                <div>
                    <h2>Account Information</h2>
                    <p>Account status and activity information.</p>
                </div>

            </div>


            <div class="profile-info-grid">

                <div class="profile-info-item">
                    <span>Account Status</span>

                    <strong class="active-account">
                        Active
                    </strong>
                </div>

                <div class="profile-info-item">
                    <span>Member Since</span>
                    <strong>March 10, 2026</strong>
                </div>

                <div class="profile-info-item">
                    <span>Last Login</span>
                    <strong>Today, 10:32 AM</strong>
                </div>

                <div class="profile-info-item">
                    <span>Password Updated</span>
                    <strong>June 20, 2026</strong>
                </div>

            </div>

        </section>


        <section class="profile-card">

            <div class="profile-card-header">

                <div>
                    <h2>Security</h2>
                    <p>Manage your password and account security.</p>
                </div>

            </div>


            <div class="security-action">

                <div>
                    <strong>Password</strong>

                    <p>
                        Change your account password regularly to keep
                        your account secure.
                    </p>
                </div>

                <button class="change-password-button">
                    Change Password
                </button>

            </div>

        </section>

    </div>

</div>

@endsection
