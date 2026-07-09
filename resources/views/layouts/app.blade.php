<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'IT Help Desk')</title>

    <!-- Shared CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Page-specific CSS -->
    @yield('page-css')
</head>

<body>

<div class="app-layout">

    <aside class="sidebar">

        <div class="sidebar-logo">
            <h2>IT Help Desk</h2>
        </div>
 <nav class="sidebar-menu">
            <a href="/profile"
               class="@yield('profile-active')">
                Profile
            </a>

        <nav class="sidebar-menu">
            <a href="/dashboard"
               class="@yield('dashboard-active')">
                Dashboard
            </a>

            <a href="/tickets"
               class="@yield('tickets-active')">
                Tickets
            </a>

            <a href="/tickets/create"
               class="@yield('create-ticket-active')">
                Create Ticket
            </a>

            <a href="/notifications"
               class="@yield('notifications-active')">
                Notifications
            </a>

            <a href="/reports"
               class="@yield('reports-active')">
                Reports
            </a>
        </nav>

        <div class="sidebar-bottom">
            <a href="#">Logout</a>
        </div>

    </aside>


    <main class="dashboard-main">

        <header class="topbar">

            <div>
                <h1>@yield('page-title')</h1>

                <p>
                    @yield('page-description')
                </p>
            </div>


            <div class="topbar-user">

                <span>🔔</span>

                <div>
                    <strong>Yasser Ayoub</strong>
                    <p>Administrator</p>
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
