@extends('layouts.app')

@section('title', 'Edit User | IT Help Desk')

@section('page-css')

<style>

    .edit-user-page {
        max-width: 1100px;
        margin: 0 auto;
        padding: 30px 35px 60px;
    }

    /* =====================================================
       PAGE HEADER
    ===================================================== */

    .edit-page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .edit-page-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .edit-page-icon {
        width: 52px;
        height: 52px;
        border-radius: 14px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #e8f0ff;
        color: #2563eb;

        font-size: 24px;
    }

    .edit-page-title h1 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #172033;
    }

    .edit-page-title p {
        margin: 5px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .back-users-button {
        display: inline-flex;
        align-items: center;
        gap: 7px;

        padding: 10px 16px;

        border: 1px solid #d7dce5;
        border-radius: 9px;

        background: white;
        color: #374151;

        text-decoration: none;

        font-size: 14px;
        font-weight: 600;

        transition: all 0.2s ease;
    }

    .back-users-button:hover {
        background: #f8fafc;
        border-color: #b9c2d0;
        color: #111827;
    }


    /* =====================================================
       USER SUMMARY
    ===================================================== */

    .user-summary-card {
        display: flex;
        align-items: center;
        gap: 16px;

        padding: 20px 24px;

        background: white;

        border: 1px solid #e4e8ef;
        border-radius: 14px;

        box-shadow: 0 3px 12px rgba(15, 23, 42, 0.04);

        margin-bottom: 20px;
    }

    .user-summary-avatar {
        width: 58px;
        height: 58px;

        border-radius: 50%;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #dbeafe;
        color: #2563eb;

        font-size: 18px;
        font-weight: 700;
    }

    .user-summary-info {
        flex: 1;
    }

    .user-summary-info h3 {
        margin: 0;

        font-size: 18px;
        color: #172033;
    }

    .user-summary-info p {
        margin: 4px 0 0;

        color: #6b7280;
        font-size: 14px;
    }

    .user-summary-meta {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .role-pill {
        padding: 6px 11px;

        border-radius: 20px;

        background: #eef2ff;
        color: #4338ca;

        font-size: 12px;
        font-weight: 600;
    }

    .active-pill {
        padding: 6px 11px;

        border-radius: 20px;

        background: #ecfdf3;
        color: #15803d;

        font-size: 12px;
        font-weight: 600;
    }

    .inactive-pill {
        padding: 6px 11px;

        border-radius: 20px;

        background: #fef2f2;
        color: #dc2626;

        font-size: 12px;
        font-weight: 600;
    }


    /* =====================================================
       FORM CARD
    ===================================================== */

    .edit-form-card {
        background: white;

        border: 1px solid #e4e8ef;
        border-radius: 14px;

        box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);

        overflow: hidden;
    }

    .form-card-header {
        padding: 22px 26px;

        border-bottom: 1px solid #edf0f4;
    }

    .form-card-header h2 {
        margin: 0;

        font-size: 18px;
        color: #172033;
    }

    .form-card-header p {
        margin: 5px 0 0;

        color: #6b7280;
        font-size: 13px;
    }

    .edit-form {
        padding: 28px;
    }


    /* =====================================================
       FORM GRID
    ===================================================== */

    .form-grid {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        margin-bottom: 8px;

        font-size: 13px;
        font-weight: 600;

        color: #374151;
    }

    .form-group input,
    .form-group select {
        width: 100%;

        box-sizing: border-box;

        padding: 12px 14px;

        border: 1px solid #d5dae3;
        border-radius: 9px;

        background: #ffffff;

        color: #172033;

        font-size: 14px;

        outline: none;

        transition:
            border-color 0.2s ease,
            box-shadow 0.2s ease;
    }

    .form-group input:focus,
    .form-group select:focus {

        border-color: #2563eb;

        box-shadow:
            0 0 0 3px rgba(37, 99, 235, 0.12);
    }

    .form-group input::placeholder {
        color: #9ca3af;
    }

    .form-help {
        margin-top: 6px;

        color: #6b7280;

        font-size: 12px;
        line-height: 1.5;
    }


    /* =====================================================
       PASSWORD SECTION
    ===================================================== */

    .password-section {

        margin-top: 30px;
        padding-top: 25px;

        border-top: 1px solid #edf0f4;
    }

    .password-section-header {
        margin-bottom: 18px;
    }

    .password-section-header h3 {
        margin: 0;

        font-size: 16px;

        color: #172033;
    }

    .password-section-header p {
        margin: 5px 0 0;

        font-size: 13px;

        color: #6b7280;
    }


    /* =====================================================
       ALERTS
    ===================================================== */

    .alert {
        margin: 20px 28px 0;

        padding: 13px 15px;

        border-radius: 9px;

        font-size: 14px;
    }

    .alert-success {
        background: #ecfdf3;

        border: 1px solid #bbf7d0;

        color: #166534;
    }

    .alert-danger {
        background: #fef2f2;

        border: 1px solid #fecaca;

        color: #991b1b;
    }

    .alert-danger ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }


    /* =====================================================
       FORM ACTIONS
    ===================================================== */

    .form-actions {

        display: flex;

        justify-content: flex-end;

        align-items: center;

        gap: 12px;

        margin-top: 30px;

        padding-top: 22px;

        border-top: 1px solid #edf0f4;
    }

    .cancel-button {
        display: inline-flex;

        align-items: center;
        justify-content: center;

        padding: 11px 20px;

        border: 1px solid #d5dae3;

        border-radius: 9px;

        background: white;

        color: #374151;

        text-decoration: none;

        font-size: 14px;
        font-weight: 600;

        transition: all 0.2s ease;
    }

    .cancel-button:hover {
        background: #f8fafc;
        border-color: #b9c2d0;
    }

    .save-button {

        border: none;

        padding: 11px 22px;

        border-radius: 9px;

        background: #2563eb;

        color: white;

        font-size: 14px;

        font-weight: 600;

        cursor: pointer;

        transition: all 0.2s ease;
    }

    .save-button:hover {
        background: #1d4ed8;

        transform: translateY(-1px);

        box-shadow:
            0 4px 10px rgba(37, 99, 235, 0.25);
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media (max-width: 800px) {

        .edit-user-page {
            padding: 20px;
        }

        .edit-page-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 15px;
        }

        .user-summary-card {
            align-items: flex-start;
            flex-direction: column;
        }

        .user-summary-meta {
            flex-wrap: wrap;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-group.full-width {
            grid-column: auto;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .cancel-button,
        .save-button {
            width: 100%;
        }
    }

</style>

@endsection


@section('content')

<div class="edit-user-page">


    {{-- =====================================================
         PAGE HEADER
    ====================================================== --}}

    <div class="edit-page-header">

        <div class="edit-page-title">

            <div class="edit-page-icon">
                ✎
            </div>

            <div>

                <h1>
                    Edit User
                </h1>

                <p>
                    Update account information and permissions.
                </p>

            </div>

        </div>


        <a
            href="{{ route('UserManagementpage') }}"
            class="back-users-button"
        >
            ← Back to Users
        </a>

    </div>



    {{-- =====================================================
         USER SUMMARY
    ====================================================== --}}

    <div class="user-summary-card">

        <div class="user-summary-avatar">

            {{ strtoupper(substr($user->Name, 0, 2)) }}

        </div>


        <div class="user-summary-info">

            <h3>
                {{ $user->Name }}
            </h3>

            <p>
                {{ $user->Email }}
            </p>

        </div>


        <div class="user-summary-meta">

            <span class="role-pill">

                {{ $user->role?->Name ?? 'No Role' }}

            </span>


            @if ($user->IsActive)

                <span class="active-pill">
                    ● Active
                </span>

            @else

                <span class="inactive-pill">
                    ● Inactive
                </span>

            @endif

        </div>

    </div>



    {{-- =====================================================
         SUCCESS MESSAGE
    ====================================================== --}}

    @if (session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

    @endif



    {{-- =====================================================
         VALIDATION ERRORS
    ====================================================== --}}

    @if ($errors->any())

        <div class="alert alert-danger">

            <strong>
                Please correct the following:
            </strong>

            <ul>

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif



    {{-- =====================================================
         FORM
    ====================================================== --}}

    <div class="edit-form-card">


        <div class="form-card-header">

            <h2>
                Account Information
            </h2>

            <p>
                Update the user's basic information and system role.
            </p>

        </div>



        <form
            action="{{ route('admin.users.update', $user->Id) }}"
            method="POST"
            class="edit-form"
        >

            @csrf

            @method('PUT')


            {{-- BASIC INFORMATION --}}

            <div class="form-grid">


                {{-- NAME --}}

                <div class="form-group">

                    <label for="Name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="Name"
                        name="Name"
                        value="{{ old('Name', $user->Name) }}"
                        placeholder="Enter full name"
                        required
                    >

                </div>



                {{-- EMAIL --}}

                <div class="form-group">

                    <label for="Email">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="Email"
                        name="Email"
                        value="{{ old('Email', $user->Email) }}"
                        placeholder="employee@company.com"
                        required
                    >

                </div>



                {{-- DEPARTMENT --}}

                <div class="form-group">

                    <label for="Department">
                        Department
                    </label>

                    <input
                        type="text"
                        id="Department"
                        name="Department"
                        value="{{ old('Department', $user->Department) }}"
                        placeholder="Enter department"
                        required
                    >

                </div>



                {{-- ROLE --}}

                <div class="form-group">

                    <label for="RoleId">
                        System Role
                    </label>

                    <select
                        id="RoleId"
                        name="RoleId"
                        required
                    >

                        @foreach ($roles as $role)

                            <option
                                value="{{ $role->Id }}"
                                {{ $user->RoleId == $role->Id ? 'selected' : '' }}
                            >
                                {{ $role->Name }}
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>



            {{-- =================================================
                 PASSWORD
            ================================================== --}}

            <div class="password-section">

                <div class="password-section-header">

                    <h3>
                        Change Password
                    </h3>

                    <p>
                        Leave these fields empty if you want to keep
                        the user's current password.
                    </p>

                </div>


                <div class="form-grid">


                    {{-- PASSWORD --}}

                    <div class="form-group">

                        <label for="Password">
                            New Password
                        </label>

                        <input
                            type="password"
                            id="Password"
                            name="Password"
                            placeholder="Enter new password"
                        >

                        <span class="form-help">
                            Minimum 8 characters.
                        </span>

                    </div>



                    {{-- CONFIRM PASSWORD --}}

                    <div class="form-group">

                        <label for="Password_confirmation">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            id="Password_confirmation"
                            name="Password_confirmation"
                            placeholder="Repeat new password"
                        >

                    </div>

                </div>

            </div>



            {{-- =================================================
                 ACTIONS
            ================================================== --}}

            <div class="form-actions">

                <a
                    href="{{ route('UserManagementpage') }}"
                    class="cancel-button"
                >
                    Cancel
                </a>


                <button
                    type="submit"
                    class="save-button"
                >
                    Save Changes
                </button>

            </div>


        </form>

    </div>

</div>

@endsection

