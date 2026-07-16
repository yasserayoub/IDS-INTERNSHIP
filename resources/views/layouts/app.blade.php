@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'IT Help Desk')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    @yield('page-css')
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="sidebar-logo">
            <h2>IT Help Desk</h2>
        </div>

        <nav class="sidebar-menu">

            <!-- Everyone -->
            <a href="/profile"
               class="@yield('profile-active')">
                Profile
            </a>

            <!-- Administrator only -->
            @if(Auth::check() && Auth::user()->role->Name == 'Administrator')

                <a href="/users"
                   class="@yield('users-active')">
                    Users
                </a>

                <a href="{{ route('admin.dashboard') }}"
                   class="@yield('admin-dashboard-active')">
                    Admin Dashboard
                </a>

            @endif


            <!-- Administrator + IT Support -->
            @if(Auth::check() &&
                (Auth::user()->role->Name == 'Administrator' ||
                 Auth::user()->role->Name == 'IT Support'))

                <a href="/dashboard"
                   class="@yield('dashboard-active')">
                    Dashboard
                </a>

                <a href="/tickets"
                   class="@yield('tickets-active')">
                    Tickets
                </a>

                <a href="/notifications"
                   class="@yield('notifications-active')">
                    Notifications
                </a>

                <a href="/reports"
                   class="@yield('reports-active')">
                    Reports
                </a>

            @endif


            <!-- Employee only -->
            @if(Auth::check() && Auth::user()->role->Name == 'Employee')

                <a href="/tickets/create"
                   class="@yield('create-ticket-active')">
                    Create Ticket
                </a>

                <a href="{{ route('employee.dashboard') }}"
                   class="@yield('my-tickets-active')">
                    My Tickets
                </a>

                <a href="/notifications"
                   class="@yield('notifications-active')">
                    Notifications
                </a>

            @endif

        </nav>

        <div class="sidebar-bottom">

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-button">
                    Logout
                </button>
            </form>

        </div>

    </aside>

    <main class="dashboard-main">

        <header class="topbar">

            <div>
                <h1>@yield('page-title')</h1>
                <p>@yield('page-description')</p>
            </div>

            <div class="topbar-user">

                <span>🔔</span>

                <div>
                    <strong>{{ Auth::user()->Name }}</strong>
                    <p>{{ Auth::user()->role->Name }}</p>
                </div>

            </div>

        </header>

        <section class="dashboard-content">

            @yield('content')

        </section>

    </main>

</div>

</body>
</html>
