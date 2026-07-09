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
            + Add User
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

                <tr>

                    <td>
                        <div class="user-cell">

                            <div class="user-avatar">
                                MB
                            </div>

                            <div>
                                <strong>Michael Brown</strong>
                                <span>michael.brown@company.com</span>
                            </div>

                        </div>
                    </td>

                    <td>Finance</td>

                    <td>
                        <span class="role-badge employee-role">
                            Employee
                        </span>
                    </td>

                    <td>
                        <span class="status-badge active-status">
                            Active
                        </span>
                    </td>

                    <td>Jun 15, 2026</td>

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


                <tr>

                    <td>
                        <div class="user-cell">

                            <div class="user-avatar">
                                AH
                            </div>

                            <div>
                                <strong>Ahmad Hassan</strong>
                                <span>ahmad.hassan@company.com</span>
                            </div>

                        </div>
                    </td>

                    <td>Information Technology</td>

                    <td>
                        <span class="role-badge agent-role">
                            IT Support Agent
                        </span>
                    </td>

                    <td>
                        <span class="status-badge active-status">
                            Active
                        </span>
                    </td>

                    <td>May 2, 2026</td>

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


                <tr>

                    <td>
                        <div class="user-cell">

                            <div class="user-avatar">
                                SA
                            </div>

                            <div>
                                <strong>Sarah Ali</strong>
                                <span>sarah.ali@company.com</span>
                            </div>

                        </div>
                    </td>

                    <td>Information Technology</td>

                    <td>
                        <span class="role-badge agent-role">
                            IT Support Agent
                        </span>
                    </td>

                    <td>
                        <span class="status-badge active-status">
                            Active
                        </span>
                    </td>

                    <td>Apr 18, 2026</td>

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


                <tr>

                    <td>
                        <div class="user-cell">

                            <div class="user-avatar">
                                JD
                            </div>

                            <div>
                                <strong>John Davis</strong>
                                <span>john.davis@company.com</span>
                            </div>

                        </div>
                    </td>

                    <td>Human Resources</td>

                    <td>
                        <span class="role-badge manager-role">
                            Manager
                        </span>
                    </td>

                    <td>
                        <span class="status-badge active-status">
                            Active
                        </span>
                    </td>

                    <td>Mar 22, 2026</td>

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


                <tr>

                    <td>
                        <div class="user-cell">

                            <div class="user-avatar">
                                RW
                            </div>

                            <div>
                                <strong>Robert Wilson</strong>
                                <span>robert.wilson@company.com</span>
                            </div>

                        </div>
                    </td>

                    <td>Operations</td>

                    <td>
                        <span class="role-badge employee-role">
                            Employee
                        </span>
                    </td>

                    <td>
                        <span class="status-badge inactive-status">
                            Inactive
                        </span>
                    </td>

                    <td>Feb 10, 2026</td>

                    <td>
                        <div class="user-actions">
                            <button class="edit-button">
                                Edit
                            </button>

                            <button class="activate-button">
                                Activate
                            </button>
                        </div>
                    </td>

                </tr>

            </tbody>

        </table>

    </div>


    <div class="users-pagination">

        <span>
            Showing 1–5 of 84 users
        </span>

        <div>
            <button disabled>
                Previous
            </button>

            <button class="page-active">
                1
            </button>

            <button>
                2
            </button>

            <button>
                3
            </button>

            <button>
                Next
            </button>
        </div>

    </div>

</section>

@endsection
