@extends('layouts.app')

@section('title', 'User Management | IT Help Desk')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
@endsection

@section('page-title', 'User Management')

@section(
    'page-description',
    'Manage system users, roles, and account status.'
)

@section('content')

<div class="users-toolbar">

    <div class="users-search">
        <input
            type="text"
            placeholder="Search users by name or email..."
        >
    </div>

    <div class="users-filters">

        <select>
            <option>All Roles</option>
            <option>Employee</option>
            <option>IT Support Agent</option>
            <option>Manager</option>
            <option>Administrator</option>
        </select>

        <select>
            <option>All Statuses</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>

        <button class="add-user-button">
            <a   href="{{ route('adminCreateUser') }}">
    + Add User
</a>
        </button>

    </div>

</div>


<div class="users-stats">

    <div class="user-stat-card">
        <span>Total Users</span>
        <h2>84</h2>
        <p>All registered accounts</p>
    </div>

    <div class="user-stat-card">
        <span>Employees</span>
        <h2>68</h2>
        <p>Regular system users</p>
    </div>

    <div class="user-stat-card">
        <span>Support Agents</span>
        <h2>12</h2>
        <p>IT support team members</p>
    </div>

    <div class="user-stat-card">
        <span>Inactive Accounts</span>
        <h2>4</h2>
        <p>Currently disabled accounts</p>
    </div>

</div>


<section class="users-card">

    <div class="users-card-header">

        <div>
            <h2>System Users</h2>
            <p>View and manage registered users.</p>
        </div>

        <span class="user-count">
            84 users
        </span>

    </div>


    <div class="users-table-wrapper">

        <table class="users-table">

            <thead>
                <tr>
                    <th>User</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>


            <tbody>

               <tbody>

@foreach ($users as $user)

<tr>

    <td>
        <div class="user-cell">

            <div class="user-avatar">
                {{ strtoupper(substr($user->Name, 0, 2)) }}
            </div>

            <div>
                <strong>{{ $user->Name }}</strong>
                <span>{{ $user->Email }}</span>
            </div>

        </div>
    </td>

    <td>{{ $user->Department }}</td>

    <td>
        <span class="role-badge">
            {{ $user->role->Name }}
        </span>
    </td>

    <td>
        @if($user->IsActive)
            <span class="status-badge active-status">
                Active
            </span>
        @else
            <span class="status-badge inactive-status">
                Inactive
            </span>
        @endif
    </td>

    <td>{{ $user->CreatedAt }}</td>

    <td>
        <div class="user-actions">

            <button class="edit-button">
                Edit
            </button>

            <button class="deactivate-button">
                Deactivate
            </button>

        </div>
    </td>

</tr>

@endforeach

</tbody>


               

</section>

@endsection
