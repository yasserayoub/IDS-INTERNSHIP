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

    {{-- Search --}}
    <div class="users-search">
        <input
            type="text"
            id="userSearch"
            placeholder="Search users by name or email..."
        >
    </div>


    <div class="users-filters">

        {{-- Role Filter --}}
        <select id="roleFilter">
            <option value="">All Roles</option>

            <option value="Employee">
                Employee
            </option>

            <option value="IT Support">
                IT Support
            </option>

            <option value="IT Manager">
                IT Manager
            </option>

            <option value="Administrator">
                Administrator
            </option>
        </select>


        {{-- Status Filter --}}
        <select id="statusFilter">
            <option value="">All Statuses</option>

            <option value="Active">
                Active
            </option>

            <option value="Inactive">
                Inactive
            </option>
        </select>


        {{-- Add User --}}
        <a
            href="{{ route('adminCreateUser') }}"
            class="add-user-button"
        >
            + Add User
        </a>

    </div>

</div>



{{-- ========================================================= --}}
{{-- USER STATISTICS --}}
{{-- ========================================================= --}}

<div class="users-stats">

    {{-- Total Users --}}
    <div class="user-stat-card">

        <span>Total Users</span>

        <h2>
            {{ $totalUsers }}
        </h2>

        <p>
            All registered accounts
        </p>

    </div>


    {{-- Employees --}}
    <div class="user-stat-card">

        <span>Employees</span>

        <h2>
            {{ $employeeCount }}
        </h2>

        <p>
            Regular system users
        </p>

    </div>


    {{-- Support Agents --}}
    <div class="user-stat-card">

        <span>Support Agents</span>

        <h2>
            {{ $supportCount }}
        </h2>

        <p>
            IT support team members
        </p>

    </div>


    {{-- Inactive Accounts --}}
    <div class="user-stat-card">

        <span>Inactive Accounts</span>

        <h2>
            {{ $inactiveCount }}
        </h2>

        <p>
            Currently disabled accounts
        </p>

    </div>

</div>



{{-- ========================================================= --}}
{{-- USERS TABLE --}}
{{-- ========================================================= --}}

<section class="users-card">

    <div class="users-card-header">

        <div>

            <h2>
                System Users
            </h2>

            <p>
                View and manage registered users.
            </p>

        </div>


        <span class="user-count">

            {{ $totalUsers }}

            {{ $totalUsers === 1 ? 'user' : 'users' }}

        </span>

    </div>



    <div class="users-table-wrapper">

        <table class="users-table">

            <thead>

                <tr>

                    <th>
                        User
                    </th>

                    <th>
                        Department
                    </th>

                    <th>
                        Role
                    </th>

                    <th>
                        Status
                    </th>

                    <th>
                        Created
                    </th>

                    <th>
                        Actions
                    </th>

                </tr>

            </thead>


            <tbody id="usersTableBody">

                @forelse ($users as $user)

                    <tr
                        class="user-row"
                        data-name="{{ strtolower($user->Name) }}"
                        data-email="{{ strtolower($user->Email) }}"
                        data-role="{{ $user->role?->Name }}"
                        data-status="{{ $user->IsActive ? 'Active' : 'Inactive' }}"
                    >

                        {{-- USER --}}
                        <td>

                            <div class="user-cell">

                                <div class="user-avatar">

                                    {{ strtoupper(
                                        substr($user->Name, 0, 2)
                                    ) }}

                                </div>


                                <div>

                                    <strong>
                                        {{ $user->Name }}
                                    </strong>

                                    <span>
                                        {{ $user->Email }}
                                    </span>

                                </div>

                            </div>

                        </td>


                        {{-- DEPARTMENT --}}
                        <td>

                            {{ $user->Department ?? '—' }}

                        </td>


                        {{-- ROLE --}}
                        <td>

                            <span class="role-badge">

                                {{ $user->role?->Name ?? 'No Role' }}

                            </span>

                        </td>


                        {{-- STATUS --}}
                        <td>

                            @if ($user->IsActive)

                                <span class="status-badge active-status">
                                    Active
                                </span>

                            @else

                                <span class="status-badge inactive-status">
                                    Inactive
                                </span>

                            @endif

                        </td>


                        {{-- CREATED --}}
                        <td>

                            {{ $user->CreatedAt
                                ? \Carbon\Carbon::parse($user->CreatedAt)->format('M d, Y')
                                : '—'
                            }}

                        </td>


                       <td>
    <div class="user-actions">

        {{-- EDIT --}}
        <a
            href="{{ route('admin.users.edit', $user->Id) }}"
            class="edit-button"
        >
            Edit
        </a>


        {{-- ACTIVATE / DEACTIVATE --}}
        <form
            action="{{ route('admin.users.toggle-status', $user->Id) }}"
            method="POST"
            style="display: inline;"
        >

            @csrf

            @method('PATCH')

            @if ($user->IsActive)

                <button
                    type="submit"
                    class="deactivate-button"
                    onclick="return confirm('Are you sure you want to deactivate this user?')"
                >
                    Deactivate
                </button>

            @else

                <button
                    type="submit"
                    class="deactivate-button"
                    onclick="return confirm('Are you sure you want to activate this user?')"
                >
                    Activate
                </button>

            @endif

        </form>

    </div>
</td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="6"
                            style="text-align: center; padding: 30px;"
                        >

                            No users found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</section>



{{-- ========================================================= --}}
{{-- SEARCH AND FILTER JAVASCRIPT --}}
{{-- ========================================================= --}}

<script>

document.addEventListener('DOMContentLoaded', function () {

    const searchInput =
        document.getElementById('userSearch');

    const roleFilter =
        document.getElementById('roleFilter');

    const statusFilter =
        document.getElementById('statusFilter');

    const rows =
        document.querySelectorAll('.user-row');


    function filterUsers() {

        const search =
            searchInput.value
                .toLowerCase()
                .trim();

        const selectedRole =
            roleFilter.value;

        const selectedStatus =
            statusFilter.value;


        rows.forEach(function (row) {

            const name =
                row.dataset.name || '';

            const email =
                row.dataset.email || '';

            const role =
                row.dataset.role || '';

            const status =
                row.dataset.status || '';


            const matchesSearch =
                name.includes(search) ||
                email.includes(search);


            const matchesRole =
                selectedRole === '' ||
                role === selectedRole;


            const matchesStatus =
                selectedStatus === '' ||
                status === selectedStatus;


            if (
                matchesSearch &&
                matchesRole &&
                matchesStatus
            ) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    }


    searchInput.addEventListener(
        'input',
        filterUsers
    );


    roleFilter.addEventListener(
        'change',
        filterUsers
    );


    statusFilter.addEventListener(
        'change',
        filterUsers
    );

});

</script>

@endsection
