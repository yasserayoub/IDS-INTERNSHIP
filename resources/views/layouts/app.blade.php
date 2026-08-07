@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'IT Help Desk')
    </title>


    {{-- ===================================================== --}}
    {{-- GLOBAL CSS --}}
    {{-- ===================================================== --}}

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    {{-- PAGE-SPECIFIC CSS --}}
    @yield('page-css')

</head>


<body>


<div class="app-layout">


    {{-- ===================================================== --}}
    {{-- SIDEBAR --}}
    {{-- ===================================================== --}}

    <aside class="sidebar">


        {{-- LOGO --}}
        <div class="sidebar-logo">

            <h2>
                IT Help Desk
            </h2>

        </div>



        {{-- ================================================= --}}
        {{-- SIDEBAR MENU --}}
        {{-- ================================================= --}}

        <nav class="sidebar-menu">


            {{-- ================================================= --}}
            {{-- PROFILE - EVERYONE --}}
            {{-- ================================================= --}}

            <a
                href="/profile"
                class="@yield('profile-active')"
            >
                Profile
            </a>



            {{-- ================================================= --}}
            {{-- ADMINISTRATOR --}}
            {{-- ================================================= --}}

            @if(
                Auth::check() &&
                Auth::user()->role->Name == 'Administrator'
            )


                {{-- ADMIN DASHBOARD --}}
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="@yield('admin-dashboard-active')"
                >
                    Dashboard
                </a>


                {{-- USERS --}}
                <a
                    href="{{ route('UserManagementpage') }}"
                    class="@yield('users-active')"
                >
                    Users
                </a>


                {{-- TICKETS --}}
                <a
                    href="{{ route('allticketspage') }}"
                    class="@yield('tickets-active')"
                >
                    Tickets
                </a>


                {{-- ACTIVITY LOG --}}
                <a
                    href="{{ route('activity.logs') }}"
                    class="@yield('activity-active')"
                >
                    Activity Log
                </a>


                {{-- TICKET HISTORIES --}}
                <a
                    href="{{ route('ticket.histories') }}"
                    class="@yield('history-active')"
                >
                    Ticket Histories
                </a>



                <a
                   href="{{ route('notifications') }}"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>


                {{-- REPORTS --}}
                <a
                    href="/reports"
                    class="@yield('reports-active')"
                >
                    Reports
                </a>


            @endif



            {{-- ================================================= --}}
            {{-- IT MANAGER --}}
            {{-- ================================================= --}}

            @if(
                Auth::check() &&
                Auth::user()->role->Name == 'IT Manager'
            )


                {{-- MANAGER DASHBOARD --}}
                <a
                    href="{{ route('manager.dashboard') }}"
                    class="@yield('dashboard-active')"
                >
                    Dashboard
                </a>


                {{-- ALL TICKETS --}}
                <a
                    href="{{ route('allticketspage') }}"
                    class="@yield('tickets-active')"
                >
                    Tickets
                </a>


                {{-- ACTIVITY LOG --}}
                <a
                    href="{{ route('activity.logs') }}"
                    class="@yield('activity-active')"
                >
                    Activity Log
                </a>


                {{-- TICKET HISTORIES --}}
                <a
                    href="{{ route('ticket.histories') }}"
                    class="@yield('history-active')"
                >
                    Ticket Histories
                </a>


                {{-- NOTIFICATIONS --}}
                <a
                    href="/notifications"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>


                {{-- REPORTS --}}
                <a
                    href="/reports"
                    class="@yield('reports-active')"
                >
                    Reports
                </a>


            @endif



            {{-- ================================================= --}}
            {{-- IT SUPPORT --}}
            {{-- ================================================= --}}

            @if(
                Auth::check() &&
                Auth::user()->role->Name == 'IT Support'
            )


                {{-- ASSIGNED TICKETS --}}
                <a
                    href="{{ route('support.tickets') }}"
                    class="@yield('tickets-active')"
                >
                    My Assigned Tickets
                </a>


                {{-- NOTIFICATIONS --}}
                <a
                    href="/notifications"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>


            @endif



            {{-- ================================================= --}}
            {{-- EMPLOYEE --}}
            {{-- ================================================= --}}

            @if(
                Auth::check() &&
                Auth::user()->role->Name == 'Employee'
            )


                {{-- CREATE TICKET --}}
                <a
                    href="{{ route('CreateTicket') }}"
                    class="@yield('create-ticket-active')"
                >
                    Create Ticket
                </a>


                {{-- MY TICKETS --}}
                <a
                    href="{{ route('employee.dashboard') }}"
                    class="@yield('my-tickets-active')"
                >
                    My Tickets
                </a>


                {{-- NOTIFICATIONS --}}
                <a
                    href="/notifications"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>


            @endif


        </nav>



        {{-- ================================================= --}}
        {{-- LOGOUT --}}
        {{-- ================================================= --}}

        <div class="sidebar-bottom">


            <form
                action="{{ route('logout') }}"
                method="POST"
            >

                @csrf


                <button
                    type="submit"
                    class="logout-button"
                >

                    Logout

                </button>


            </form>


        </div>


    </aside>



    {{-- ===================================================== --}}
    {{-- MAIN CONTENT --}}
    {{-- ===================================================== --}}

    <main class="dashboard-main">


        {{-- ================================================= --}}
        {{-- TOP BAR --}}
        {{-- ================================================= --}}

        <header class="topbar">


            {{-- PAGE INFORMATION --}}
            <div>


                <h1>
                    @yield('page-title')
                </h1>


                <p>
                    @yield('page-description')
                </p>


            </div>



            {{-- USER INFORMATION --}}
            <div class="topbar-user">


                {{-- NOTIFICATION ICON --}}
                <a
                    href="/notifications"
                    style="text-decoration: none;"
                >
                    @php
    $unreadCount = Auth::user()
        ->notifications()
        ->where('IsRead', false)
        ->count();
@endphp

<div class="position-relative">

    <span style="font-size:22px;">
        🔔
    </span>

    @if($unreadCount > 0)

        <span
            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
        >
            {{ $unreadCount }}
        </span>

    @endif

</div>
                </a>


                {{-- USER --}}
                <div>


                    <strong>

                        {{ Auth::user()->Name }}

                    </strong>


                    <p>

                        {{ Auth::user()->role->Name }}

                    </p>


                </div>


            </div>


        </header>



        {{-- ================================================= --}}
        {{-- PAGE CONTENT --}}
        {{-- ================================================= --}}

        <section class="dashboard-content">


            {{-- SUCCESS MESSAGE --}}
            @if(session('success'))

                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert"
                >

                    {{ session('success') }}


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif



            {{-- ERROR MESSAGE --}}
            @if(session('error'))

                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert"
                >

                    {{ session('error') }}


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                    ></button>

                </div>

            @endif



            {{-- VALIDATION ERRORS --}}
            @if($errors->any())

                <div
                    class="alert alert-danger"
                    role="alert"
                >

                    <strong>
                        Please fix the following errors:
                    </strong>


                    <ul class="mb-0 mt-2">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif



            {{-- ACTUAL PAGE --}}
            @yield('content')


        </section>


    </main>


</div>



{{-- ========================================================= --}}
{{-- JAVASCRIPT --}}
{{-- ========================================================= --}}

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
></script>


{{-- PAGE-SPECIFIC JAVASCRIPT --}}
@yield('page-js')


</body>

</html>
