@php
    use Illuminate\Support\Facades\Auth;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        @yield('title', 'IT Help Desk')
    </title>

    {{-- GLOBAL CSS --}}
    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

    {{-- BOOTSTRAP --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    {{-- PAGE-SPECIFIC CSS --}}
    @yield('page-css')

</head>


<body>


<div class="app-layout">


    {{-- ========================================================= --}}
    {{-- SIDEBAR --}}
    {{-- ========================================================= --}}

    <aside class="sidebar">

        <div class="sidebar-logo">
            <h2>
                IT Help Desk
            </h2>
        </div>


        <nav class="sidebar-menu">


            {{-- PROFILE - EVERYONE --}}

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

                <a
                    href="{{ route('admin.dashboard') }}"
                    class="@yield('admin-dashboard-active')"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('UserManagementpage') }}"
                    class="@yield('users-active')"
                >
                    Users
                </a>

                <a
                    href="{{ route('allticketspage') }}"
                    class="@yield('tickets-active')"
                >
                    Tickets
                </a>

                <a
                    href="{{ route('activity.logs') }}"
                    class="@yield('activity-active')"
                >
                    Activity Log
                </a>

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

                <a
                    href="{{ route('manager.dashboard') }}"
                    class="@yield('dashboard-active')"
                >
                    Dashboard
                </a>

                <a
                    href="{{ route('allticketspage') }}"
                    class="@yield('tickets-active')"
                >
                    Tickets
                </a>

                <a
                    href="{{ route('activity.logs') }}"
                    class="@yield('activity-active')"
                >
                    Activity Log
                </a>

                <a
                    href="{{ route('ticket.histories') }}"
                    class="@yield('history-active')"
                >
                    Ticket Histories
                </a>

                <a
                    href="/notifications"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>

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

                <a
                    href="{{ route('support.tickets') }}"
                    class="@yield('tickets-active')"
                >
                    My Assigned Tickets
                </a>

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

                <a
                    href="{{ route('ai.chat') }}"
                    class="@yield('ai-assistant-active')"
                >
                    🤖 AI Assistant
                </a>

                <a
                    href="{{ route('CreateTicket') }}"
                    class="@yield('create-ticket-active')"
                >
                    Create Ticket
                </a>

                <a
                    href="{{ route('employee.dashboard') }}"
                    class="@yield('my-tickets-active')"
                >
                    My Tickets
                </a>

                <a
                    href="/notifications"
                    class="@yield('notifications-active')"
                >
                    Notifications
                </a>

            @endif


        </nav>


        {{-- LOGOUT --}}

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


    {{-- ========================================================= --}}
    {{-- MAIN CONTENT --}}
    {{-- ========================================================= --}}

    <main class="dashboard-main">


        {{-- TOP BAR --}}

        <header class="topbar">


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


                {{-- NOTIFICATIONS --}}

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
{{-- GLOBAL AI HELP DESK ASSISTANT --}}
{{-- ========================================================= --}}

@if(
    Auth::check() &&
    in_array(
        Auth::user()->role->Name,
        ['Administrator', 'IT Manager', 'IT Support', 'Employee']
    )
)

    {{-- FLOATING BUTTON --}}
    <button
        id="aiAssistantToggle"
        type="button"
        title="Open AI Help Desk Assistant"
        aria-label="Open AI Help Desk Assistant"
    >
        🤖
    </button>


    {{-- AI ASSISTANT PANEL --}}
    <div
        id="aiAssistantPanel"
        aria-hidden="true"
    >

        {{-- HEADER --}}
        <div class="ai-assistant-header">

            <div class="ai-assistant-header-info">

                <div class="ai-assistant-title">
                    🤖 IT Help Desk Assistant
                </div>

                <div class="ai-assistant-subtitle">
                    AI-powered IT support
                </div>

            </div>


            <div class="ai-assistant-header-actions">

                {{-- DELETE CHAT --}}
                <button
                    type="button"
                    id="aiAssistantClear"
                    title="Delete chat"
                    aria-label="Delete chat"
                >
                    🗑️
                </button>


                {{-- CLOSE --}}
                <button
                    type="button"
                    id="aiAssistantClose"
                    title="Close assistant"
                    aria-label="Close assistant"
                >
                    ×
                </button>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- MESSAGES --}}
        {{-- ================================================= --}}

        <div
            id="aiAssistantMessages"
            class="ai-assistant-messages"
        >

            {{-- SIMPLE WELCOME MESSAGE --}}
            <div class="ai-assistant-message ai">

                <div class="ai-assistant-bubble ai-welcome">

                    <div class="ai-welcome-title">
                        👋 Hello!
                    </div>

                    <div class="ai-welcome-text">
                        I'm your
                        <strong>IT Help Desk Assistant</strong>.
                    </div>

                    <div class="ai-welcome-text">
                        Tell me about your IT problem and I'll help you
                        troubleshoot it step by step.
                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================= --}}
        {{-- INPUT --}}
        {{-- ================================================= --}}

        <div class="ai-assistant-input-area">

            <form id="aiAssistantForm">

                @csrf

                <input
                    type="text"
                    id="aiAssistantInput"
                    placeholder="Describe your IT problem..."
                    maxlength="2000"
                    autocomplete="off"
                >

                <button
                    type="submit"
                    id="aiAssistantSend"
                >
                    Send
                </button>

            </form>

        </div>

    </div>


    {{-- ================================================= --}}
    {{-- AI ASSISTANT CSS --}}
    {{-- ================================================= --}}

  <style>

/* =========================================================
   AI HELP DESK ASSISTANT
   COMPLETE STYLES
   ========================================================= */


/* =========================================================
   FLOATING BUTTON
   ========================================================= */

#aiAssistantToggle {
    position: fixed;

    right: 25px;
    bottom: 25px;

    width: 60px;
    height: 60px;

    padding: 0;
    margin: 0;

    border: none;
    border-radius: 50%;

    background: #2563eb;
    color: #ffffff;

    font-size: 27px;
    line-height: 1;

    cursor: pointer;

    display: flex;
    align-items: center;
    justify-content: center;

    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.20);

    z-index: 9998;

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

#aiAssistantToggle:hover {
    background: #1d4ed8;

    transform: scale(1.08);

    box-shadow:
        0 12px 30px rgba(0, 0, 0, 0.25);
}

#aiAssistantToggle:active {
    transform: scale(0.96);
}


/* =========================================================
   MAIN PANEL
   ========================================================= */

#aiAssistantPanel {
    position: fixed;

    right: 25px;
    bottom: 95px;

    width: 390px;
    height: 560px;

    max-width: calc(100vw - 30px);
    max-height: calc(100vh - 120px);

    margin: 0;
    padding: 0;

    background: #ffffff;

    border: 1px solid #e5e7eb;
    border-radius: 18px;

    overflow: hidden;

    display: none;

    flex-direction: column;

    box-sizing: border-box;

    box-shadow:
        0 20px 60px rgba(0, 0, 0, 0.20);

    z-index: 9999;
}

#aiAssistantPanel.open {
    display: flex;
}


/* =========================================================
   HEADER
   ========================================================= */

.ai-assistant-header {
    position: relative;

    display: flex;

    align-items: center;
    justify-content: space-between;

    width: 100%;

    min-height: 80px;

    margin: 0;
    padding: 15px 17px;

    background: linear-gradient(
        135deg,
        #2563eb 0%,
        #1d4ed8 100%
    );

    color: #ffffff;

    box-sizing: border-box;

    flex: 0 0 auto;
}

.ai-assistant-header-info {
    min-width: 0;

    margin: 0;
    padding: 0;
}

.ai-assistant-title {
    margin: 0;
    padding: 0;

    font-size: 16px;
    font-weight: 700;

    line-height: 1.3;

    color: #ffffff;

    white-space: nowrap;
}

.ai-assistant-subtitle {
    margin: 4px 0 0 0;
    padding: 0;

    font-size: 12px;

    line-height: 1.3;

    color: #ffffff;

    opacity: 0.88;
}


/* =========================================================
   HEADER BUTTONS
   ========================================================= */

.ai-assistant-header-actions {
    display: flex;

    align-items: center;

    gap: 7px;

    margin: 0;
    padding: 0;

    flex-shrink: 0;
}

.ai-assistant-header-actions button {
    width: 36px;
    height: 36px;

    min-width: 36px;
    min-height: 36px;

    margin: 0;
    padding: 0;

    border: none;
    border-radius: 9px;

    background: rgba(255, 255, 255, 0.14);

    color: #ffffff;

    cursor: pointer;

    font-size: 17px;
    line-height: 1;

    display: flex;

    align-items: center;
    justify-content: center;

    box-sizing: border-box;

    transition:
        background 0.2s ease,
        transform 0.2s ease;
}

.ai-assistant-header-actions button:hover {
    background: rgba(255, 255, 255, 0.25);

    transform: translateY(-1px);
}

.ai-assistant-header-actions button:active {
    transform: scale(0.95);
}


/* =========================================================
   MESSAGES AREA
   ========================================================= */

.ai-assistant-messages {
    display: block;

    width: 100%;

    height: auto;

    min-height: 0;

    flex: 1 1 auto;

    overflow-y: auto;
    overflow-x: hidden;

    margin: 0;
    padding: 16px;

    background: #f8fafc;

    box-sizing: border-box;
}


/* =========================================================
   MESSAGE ROW
   ========================================================= */

.ai-assistant-message {
    display: flex;

    width: 100%;

    height: auto !important;

    min-height: 0 !important;

    margin: 0 0 12px 0 !important;
    padding: 0 !important;

    box-sizing: border-box;

    flex: 0 0 auto !important;
}

.ai-assistant-message.ai {
    justify-content: flex-start;

    align-items: flex-start;

    height: auto !important;
    min-height: 0 !important;
}

.ai-assistant-message.user {
    justify-content: flex-end;

    align-items: flex-start;

    height: auto !important;
    min-height: 0 !important;
}


/* =========================================================
   MESSAGE BUBBLE
   ========================================================= */

.ai-assistant-bubble {
    display: block;

    width: auto;

    max-width: 82%;

    height: auto !important;

    min-height: 0 !important;

    margin: 0 !important;

    padding: 11px 14px;

    border-radius: 14px;

    font-size: 14px;

    line-height: 1.5;

    white-space: pre-wrap;

    word-break: break-word;

    overflow-wrap: break-word;

    box-sizing: border-box;

    flex: 0 0 auto !important;
}


/* =========================================================
   AI MESSAGE
   ========================================================= */

.ai-assistant-message.ai .ai-assistant-bubble {
    background: #ffffff;

    color: #1f2937;

    border: 1px solid #e5e7eb;

    border-bottom-left-radius: 4px;
}


/* =========================================================
   USER MESSAGE
   ========================================================= */

.ai-assistant-message.user .ai-assistant-bubble {
    background: #2563eb;

    color: #ffffff;

    border: none;

    border-bottom-right-radius: 4px;
}


/* =========================================================
   COMPACT WELCOME MESSAGE
   ========================================================= */

/*
   IMPORTANT:
   This section prevents the welcome message from
   stretching vertically across the entire chat window.
*/

#aiAssistantMessages .ai-assistant-message.ai {
    display: flex !important;

    align-items: flex-start !important;

    justify-content: flex-start !important;

    width: 100% !important;

    height: auto !important;

    min-height: 0 !important;

    max-height: none !important;

    margin: 0 0 12px 0 !important;

    padding: 0 !important;

    flex: 0 0 auto !important;
}


/* Welcome bubble */

#aiAssistantMessages .ai-assistant-message.ai .ai-welcome {
    display: block !important;

    width: auto !important;

    max-width: 85% !important;

    height: auto !important;

    min-height: 0 !important;

    max-height: none !important;

    margin: 0 !important;

    padding: 14px 16px !important;

    background: #ffffff !important;

    border: 1px solid #e5e7eb !important;

    border-radius: 14px !important;

    color: #1f2937 !important;

    box-sizing: border-box !important;

    flex: 0 0 auto !important;

    position: static !important;

    text-align: left !important;

    overflow: visible !important;
}


/* Welcome title */

#aiAssistantMessages .ai-welcome .ai-welcome-title {
    display: block !important;

    width: auto !important;

    height: auto !important;

    min-height: 0 !important;

    max-height: none !important;

    margin: 0 0 8px 0 !important;

    padding: 0 !important;

    font-size: 16px !important;

    font-weight: 700 !important;

    line-height: 1.3 !important;

    color: #111827 !important;

    text-align: left !important;

    box-sizing: border-box !important;

    flex: none !important;
}


/* Welcome text */

#aiAssistantMessages .ai-welcome .ai-welcome-text {
    display: block !important;

    width: auto !important;

    height: auto !important;

    min-height: 0 !important;

    max-height: none !important;

    margin: 0 !important;

    padding: 0 !important;

    font-size: 14px !important;

    font-weight: 400 !important;

    line-height: 1.5 !important;

    color: #374151 !important;

    text-align: left !important;

    box-sizing: border-box !important;

    flex: none !important;
}


/* Remove accidental spacing from elements inside welcome */

#aiAssistantMessages .ai-welcome p,
#aiAssistantMessages .ai-welcome div,
#aiAssistantMessages .ai-welcome span {
    height: auto !important;

    min-height: 0 !important;

    max-height: none !important;

    margin-top: 0;

    box-sizing: border-box;
}


/* =========================================================
   PREVENT FLEX/GLOBAL CSS FROM SPREADING WELCOME CONTENT
   ========================================================= */

#aiAssistantMessages .ai-welcome {
    justify-content: flex-start !important;

    align-items: flex-start !important;

    align-content: flex-start !important;

    flex-direction: column !important;

    gap: 0 !important;

    flex-wrap: nowrap !important;

    place-content: flex-start !important;

    place-items: flex-start !important;
}


/* =========================================================
   TYPING INDICATOR
   ========================================================= */

.ai-assistant-typing {
    display: block;

    height: auto !important;

    min-height: 0 !important;

    margin: 0;

    padding: 0;

    color: #6b7280;

    font-size: 13px;

    font-style: italic;

    line-height: 1.4;
}


/* =========================================================
   INPUT AREA
   ========================================================= */

.ai-assistant-input-area {
    display: block;

    width: 100%;

    margin: 0;
    padding: 12px;

    background: #ffffff;

    border-top: 1px solid #e5e7eb;

    box-sizing: border-box;

    flex: 0 0 auto;
}


/* =========================================================
   INPUT FORM
   ========================================================= */

#aiAssistantForm {
    display: flex;

    align-items: center;

    width: 100%;

    height: auto;

    margin: 0;
    padding: 0;

    gap: 8px;

    box-sizing: border-box;
}


/* =========================================================
   TEXT INPUT
   ========================================================= */

#aiAssistantInput {
    display: block;

    flex: 1 1 auto;

    width: 100%;

    min-width: 0;

    height: 46px;

    margin: 0;
    padding: 0 13px;

    border: 1px solid #d1d5db;

    border-radius: 11px;

    outline: none;

    background: #ffffff;

    color: #111827;

    font-family: inherit;

    font-size: 14px;

    line-height: 46px;

    box-sizing: border-box;

    transition:
        border-color 0.2s ease,
        box-shadow 0.2s ease;
}

#aiAssistantInput::placeholder {
    color: #9ca3af;

    opacity: 1;
}

#aiAssistantInput:focus {
    border-color: #2563eb;

    box-shadow:
        0 0 0 3px rgba(37, 99, 235, 0.10);
}


/* =========================================================
   SEND BUTTON
   ========================================================= */

#aiAssistantSend {
    display: flex;

    align-items: center;
    justify-content: center;

    flex: 0 0 auto;

    height: 46px;

    min-width: 78px;

    margin: 0;
    padding: 0 17px;

    border: none;

    border-radius: 11px;

    background: #2563eb;

    color: #ffffff;

    font-family: inherit;

    font-size: 14px;

    font-weight: 600;

    line-height: 1;

    cursor: pointer;

    box-sizing: border-box;

    transition:
        background 0.2s ease,
        transform 0.2s ease;
}

#aiAssistantSend:hover {
    background: #1d4ed8;

    transform: translateY(-1px);
}

#aiAssistantSend:active {
    transform: scale(0.97);
}

#aiAssistantSend:disabled {
    opacity: 0.6;

    cursor: not-allowed;

    transform: none;
}


/* =========================================================
   SCROLLBAR
   ========================================================= */

.ai-assistant-messages::-webkit-scrollbar {
    width: 7px;
}

.ai-assistant-messages::-webkit-scrollbar-track {
    background: transparent;
}

.ai-assistant-messages::-webkit-scrollbar-thumb {
    background: #cbd5e1;

    border-radius: 10px;
}

.ai-assistant-messages::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}


/* =========================================================
   MOBILE
   ========================================================= */

@media (max-width: 600px) {

    #aiAssistantPanel {
        right: 10px;
        bottom: 80px;

        width: calc(100vw - 20px);

        height: calc(100vh - 110px);

        max-width: calc(100vw - 20px);
        max-height: none;

        border-radius: 16px;
    }


    #aiAssistantToggle {
        right: 15px;
        bottom: 15px;

        width: 56px;
        height: 56px;

        font-size: 25px;
    }


    .ai-assistant-header {
        min-height: 76px;

        padding: 14px 15px;
    }


    .ai-assistant-title {
        font-size: 15px;
    }


    .ai-assistant-subtitle {
        font-size: 11px;
    }


    .ai-assistant-messages {
        padding: 14px;
    }


    .ai-assistant-bubble {
        max-width: 88%;
    }


    #aiAssistantMessages .ai-welcome {
        max-width: 90% !important;
    }


    #aiAssistantSend {
        min-width: 70px;

        padding: 0 14px;
    }
}


/* =========================================================
   SMALL MOBILE
   ========================================================= */

@media (max-width: 380px) {

    #aiAssistantPanel {
        right: 5px;
        bottom: 70px;

        width: calc(100vw - 10px);

        max-width: calc(100vw - 10px);
    }


    .ai-assistant-header {
        padding: 13px;
    }


    .ai-assistant-header-actions {
        gap: 5px;
    }


    .ai-assistant-header-actions button {
        width: 33px;
        height: 33px;

        min-width: 33px;
        min-height: 33px;
    }


    #aiAssistantForm {
        gap: 6px;
    }


    #aiAssistantInput {
        font-size: 13px;
    }


    #aiAssistantSend {
        min-width: 65px;

        padding: 0 12px;
    }
}

</style>


    {{-- ================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================= --}}

    <script>

    document.addEventListener('DOMContentLoaded', function () {

        const toggleButton =
            document.getElementById('aiAssistantToggle');

        const panel =
            document.getElementById('aiAssistantPanel');

        const closeButton =
            document.getElementById('aiAssistantClose');

        const clearButton =
            document.getElementById('aiAssistantClear');

        const messagesContainer =
            document.getElementById('aiAssistantMessages');

        const form =
            document.getElementById('aiAssistantForm');

        const input =
            document.getElementById('aiAssistantInput');

        const sendButton =
            document.getElementById('aiAssistantSend');


        const STORAGE_KEY =
            'it_help_desk_ai_chat';


        /* =====================================================
           SCROLL
        ===================================================== */

        function scrollToBottom() {

            messagesContainer.scrollTop =
                messagesContainer.scrollHeight;

        }


        /* =====================================================
           OPEN
        ===================================================== */

        function openAssistant() {

            panel.classList.add('open');

            panel.setAttribute(
                'aria-hidden',
                'false'
            );

            input.focus();

            scrollToBottom();

        }


        /* =====================================================
           CLOSE
        ===================================================== */

        function closeAssistant() {

            panel.classList.remove('open');

            panel.setAttribute(
                'aria-hidden',
                'true'
            );

        }


        /* =====================================================
           ADD MESSAGE
        ===================================================== */

        function addMessage(message, type) {

            const wrapper =
                document.createElement('div');

            wrapper.className =
                'ai-assistant-message ' + type;


            const bubble =
                document.createElement('div');

            bubble.className =
                'ai-assistant-bubble';


            bubble.textContent =
                message;


            wrapper.appendChild(bubble);

            messagesContainer.appendChild(wrapper);

            scrollToBottom();

        }


        /* =====================================================
           SAVE CHAT
        ===================================================== */

        function saveMessages() {

            const messages = [];

            const messageElements =
                messagesContainer.querySelectorAll(
                    '.ai-assistant-message'
                );


            messageElements.forEach(
                function (messageElement) {

                    const bubble =
                        messageElement.querySelector(
                            '.ai-assistant-bubble'
                        );


                    if (!bubble) {
                        return;
                    }


                    if (
                        bubble.classList.contains(
                            'ai-assistant-typing'
                        )
                    ) {
                        return;
                    }


                    messages.push({

                        type:
                            messageElement.classList.contains(
                                'user'
                            )
                                ? 'user'
                                : 'ai',

                        message:
                            bubble.textContent.trim()

                    });

                }
            );


            localStorage.setItem(
                STORAGE_KEY,
                JSON.stringify(messages)
            );

        }


        /* =====================================================
           RESET CHAT
        ===================================================== */

        function resetChatUI() {

            messagesContainer.innerHTML = `

                <div class="ai-assistant-message ai">

                    <div class="ai-assistant-bubble ai-welcome">

                        <div class="ai-welcome-title">
                            👋 Hello!
                        </div>

                        <div class="ai-welcome-text">
                            I'm your
                            <strong>IT Help Desk Assistant</strong>.
                        </div>

                        <div class="ai-welcome-text">
                            Tell me about your IT problem and I'll help you
                            troubleshoot it step by step.
                        </div>

                    </div>

                </div>

            `;

            scrollToBottom();

        }


        /* =====================================================
           LOAD CHAT
        ===================================================== */

        function loadMessages() {

            const saved =
                localStorage.getItem(
                    STORAGE_KEY
                );


            if (!saved) {

                resetChatUI();

                return;
            }


            try {

                const messages =
                    JSON.parse(saved);


                if (
                    !Array.isArray(messages) ||
                    messages.length === 0
                ) {

                    resetChatUI();

                    return;
                }


                messagesContainer.innerHTML = '';


                messages.forEach(
                    function (item) {

                        if (
                            !item.message ||
                            !item.type
                        ) {
                            return;
                        }


                        addMessage(
                            item.message,
                            item.type
                        );

                    }
                );


            } catch (error) {

                console.error(
                    'Could not load AI chat:',
                    error
                );


                localStorage.removeItem(
                    STORAGE_KEY
                );


                resetChatUI();

            }

        }


        /* =====================================================
           DELETE CHAT
        ===================================================== */

        function clearChat() {

            const confirmed =
                confirm(
                    'Are you sure you want to delete this chat?'
                );


            if (!confirmed) {
                return;
            }


            localStorage.removeItem(
                STORAGE_KEY
            );


            resetChatUI();


            input.value = '';

            input.focus();

        }


        /* =====================================================
           SEND MESSAGE
        ===================================================== */

        async function sendMessage(message) {

            message =
                message.trim();


            if (!message) {
                return;
            }


            /* USER MESSAGE */

            addMessage(
                message,
                'user'
            );


            saveMessages();


            input.value = '';

            sendButton.disabled = true;


            /* TYPING */

            const typingWrapper =
                document.createElement('div');

            typingWrapper.className =
                'ai-assistant-message ai';


            const typingBubble =
                document.createElement('div');

            typingBubble.className =
                'ai-assistant-bubble ai-assistant-typing';


            typingBubble.textContent =
                'AI is thinking...';


            typingWrapper.appendChild(
                typingBubble
            );


            messagesContainer.appendChild(
                typingWrapper
            );


            scrollToBottom();


            try {

                const csrfToken =
                    document.querySelector(
                        'input[name="_token"]'
                    )?.value;


                const response =
                    await fetch(
                        '{{ route('ai.chat.send') }}',
                        {

                            method: 'POST',

                            headers: {

                                'Content-Type':
                                    'application/json',

                                'Accept':
                                    'application/json',

                                'X-CSRF-TOKEN':
                                    csrfToken

                            },

                            body:
                                JSON.stringify({

                                    message:
                                        message

                                })

                        }
                    );


                const data =
                    await response.json();


                typingWrapper.remove();


                if (data.success) {

                    addMessage(
                        data.message,
                        'ai'
                    );

                } else {

                    addMessage(
                        data.message ||
                        'The AI assistant could not process your request.',
                        'ai'
                    );

                }


                saveMessages();


            } catch (error) {

                console.error(
                    'AI assistant error:',
                    error
                );


                typingWrapper.remove();


                addMessage(
                    'Sorry, I could not connect to the AI assistant right now.',
                    'ai'
                );


                saveMessages();

            } finally {

                sendButton.disabled =
                    false;

                input.focus();

                scrollToBottom();

            }

        }


        /* =====================================================
           BUTTON EVENTS
        ===================================================== */

        if (toggleButton) {

            toggleButton.addEventListener(
                'click',
                function () {

                    if (
                        panel.classList.contains('open')
                    ) {

                        closeAssistant();

                    } else {

                        openAssistant();

                    }

                }
            );

        }


        if (closeButton) {

            closeButton.addEventListener(
                'click',
                function () {

                    closeAssistant();

                }
            );

        }


        if (clearButton) {

            clearButton.addEventListener(
                'click',
                function () {

                    clearChat();

                }
            );

        }


        if (form) {

            form.addEventListener(
                'submit',
                function (event) {

                    event.preventDefault();

                    sendMessage(
                        input.value
                    );

                }
            );

        }


        /* =====================================================
           LOAD SAVED CHAT
        ===================================================== */

        loadMessages();


        scrollToBottom();

    });

    </script>

@endif
</body>
</html>
