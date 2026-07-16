@extends('layouts.app')

@section('title', 'Add User')

@section('page-title', 'Add New User')

@section('page-description', 'Create a new system user.')

@section('admin-dashboard-active', 'active')

@section('content')
@section('page-css')
<link rel="stylesheet" href="{{ asset('css/create.css') }}">
@endsection

<div class="card">

    <h2>Add User</h2>
    <p>Create a new account for an employee or IT support member.</p>

    <form action="{{ route('StoreUser') }}" method="POST">

        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Name</label>
                <input
                    type="text"
                    name="Name"
                    placeholder="Enter name"
                    required>
            </div>



            <div class="form-group">
                <label>Email</label>
                <input
                    type="email"
                    name="Email"
                    placeholder="Enter email"
                    required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input
                    type="password"
                    name="Password"
                    placeholder="Enter password"
                    required>
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input
                    type="password"
                    name="Password_confirmation"
                    placeholder="Confirm password"
                    required>
            </div>

            <div class="form-group">
                <label>Department</label>
                <select name="Department">

                    <option value="">Select Department</option>

                    <option value="1">Information Technology</option>
                    <option value="2">Finance</option>
                    <option value="3">Human Resources</option>
                    <option value="4">Sales</option>

                </select>
            </div>

            <div class="form-group">
                <label>Role</label>

                <select name="RoleId">
                    <option value="">Select Role</option>

                  @foreach ($roles as $role)
                    <option value="{{ $role->Id }}">{{ $role->Name }}</option>
                  @endforeach

                </select>

            </div>

            <div class="form-group">
    <label>Status</label>

    <select name="IsActive">

        <option value="1">Active</option>
        <option value="0">Inactive</option>

    </select>
</div>

        </div>

        <div class="form-buttons">

            <a href="/admin/users" class="btn-secondary">
                Cancel
            </a>

            <button type="submit" class="btn-primary">
                Create User
            </button>

        </div>

    </form>

</div>

@endsection
