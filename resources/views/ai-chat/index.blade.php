@extends('layouts.app')

@section('title', 'AI Help Desk Assistant')

@section('page-title', 'AI Help Desk Assistant')

@section('page-description', 'Get help with common IT problems using the AI support assistant.')

@section('content')

<style>
    .ai-chat-page {
        max-width: 1000px;
        margin: 0 auto;
    }

    .ai-chat-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .ai-chat-header {
        padding: 22px 26px;
        background: #f8fafc;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .ai-chat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #2563eb;
        color: white;
        font-size: 24px;
    }

    .ai-chat-header h2 {
        margin: 0;
        font-size: 20px;
        color: #111827;
    }

    .ai-chat-header p {
        margin: 4px 0 0;
        color: #6b7280;
        font-size: 14px;
    }

    .ai-chat-messages {
        height: 500px;
        overflow-y: auto;
        padding: 24px;
        background: #f9fafb;
    }

    .chat-message {
        display: flex;
        margin-bottom: 18px;
    }

    .chat-message.user {
        justify-content: flex-end;
    }

    .chat-message.ai {
        justify-content: flex-start;
    }

    .chat-bubble {
        max-width: 75%;
        padding: 13px 16px;
        border-radius: 14px;
        line-height: 1.5;
        font-size: 15px;
        white-space: pre-wrap;
    }

    .chat-message.user .chat-bubble {
        background: #2563eb;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .chat-message.ai .chat-bubble {
        background: white;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-bottom-left-radius: 4px;
    }

    .ai-chat-input-area {
        padding: 18px;
        background: white;
        border-top: 1px solid #e5e7eb;
    }

    .ai-chat-form {
        display: flex;
        gap: 10px;
    }

    .ai-chat-input {
        flex: 1;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        padding: 13px 15px;
        font-size: 15px;
        outline: none;
    }

    .ai-chat-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .ai-chat-send {
        border: none;
        border-radius: 10px;
        padding: 0 22px;
        background: #2563eb;
        color: white;
        font-weight: 600;
        cursor: pointer;
    }

    .ai-chat-send:hover {
        background: #1d4ed8;
    }

    .ai-chat-send:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .typing {
        color: #6b7280;
        font-style: italic;
    }

    .suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .suggestion-button {
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        border-radius: 999px;
        padding: 8px 13px;
        cursor: pointer;
        font-size: 13px;
    }

    .suggestion-button:hover {
        background: #f3f4f6;
    }

    @media (max-width: 700px) {
        .chat-bubble {
            max-width: 90%;
        }

        .ai-chat-form {
            flex-direction: column;
        }

        .ai-chat-send {
            height: 44px;
        }
    }
</style>


<div class="ai-chat-page">

    <div class="ai-chat-card">

        <div class="ai-chat-header">

            <div class="ai-chat-icon">
                🤖
            </div>

            <div>
                <h2>IT Help Desk Assistant</h2>

                <p>
                    Describe your IT problem and I'll help you troubleshoot it.
                </p>
            </div>

        </div>


        <div
            id="chatMessages"
            class="ai-chat-messages"
        >

            <!--
                The initial message is only displayed when
                there is no saved conversation.
            -->

            <div class="chat-message ai">

                <div class="chat-bubble">
                    Hello! 👋 I'm your IT Help Desk Assistant.

                    Tell me about your IT problem and I'll help you troubleshoot it.
                </div>

            </div>

        </div>


        <div class="ai-chat-input-area">

            <div class="suggestions">

                <button
                    type="button"
                    class="suggestion-button"
                    data-message="My laptop cannot connect to Wi-Fi."
                >
                    📶 Wi-Fi problem
                </button>


                <button
                    type="button"
                    class="suggestion-button"
                    data-message="I cannot connect to the company VPN."
                >
                    🔐 VPN problem
                </button>


                <button
                    type="button"
                    class="suggestion-button"
                    data-message="My printer is not working."
                >
                    🖨️ Printer problem
                </button>


                <button
                    type="button"
                    class="suggestion-button"
                    data-message="I cannot access my company email."
                >
                    📧 Email problem
                </button>

            </div>


            <form
                id="aiChatForm"
                class="ai-chat-form"
            >

                @csrf

                <input
                    type="text"
                    id="aiChatInput"
                    class="ai-chat-input"
                    placeholder="Describe your IT problem..."
                    autocomplete="off"
                    maxlength="2000"
                >


                <button
                    type="submit"
                    id="aiChatSend"
                    class="ai-chat-send"
                >
                    Send
                </button>

            </form>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('aiChatForm');
    const input = document.getElementById('aiChatInput');
    const sendButton = document.getElementById('aiChatSend');
    const messages = document.getElementById('chatMessages');

    /*
    |--------------------------------------------------------------------------
    | Persistent Chat Storage
    |--------------------------------------------------------------------------
    |
    | The conversation is stored in the browser's localStorage.
    |
    | This means the chat will remain when:
    |
    | - The user changes pages
    | - The user returns to the assistant
    | - The browser page is refreshed
    |
    */

    const CHAT_STORAGE_KEY = 'it_help_desk_ai_chat';


    /*
    |--------------------------------------------------------------------------
    | Save Chat
    |--------------------------------------------------------------------------
    */

    function saveChat() {

        try {

            localStorage.setItem(
                CHAT_STORAGE_KEY,
                messages.innerHTML
            );

        } catch (error) {

            console.warn(
                'Could not save AI chat history.',
                error
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Load Chat
    |--------------------------------------------------------------------------
    */

    function loadChat() {

        try {

            const savedChat =
                localStorage.getItem(
                    CHAT_STORAGE_KEY
                );


            if (savedChat) {

                messages.innerHTML = savedChat;

                messages.scrollTop =
                    messages.scrollHeight;

            }

        } catch (error) {

            console.warn(
                'Could not load AI chat history.',
                error
            );

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Add Message
    |--------------------------------------------------------------------------
    */

    function addMessage(message, type) {

        const wrapper =
            document.createElement('div');

        wrapper.className =
            'chat-message ' + type;


        const bubble =
            document.createElement('div');

        bubble.className =
            'chat-bubble';


        /*
        |--------------------------------------------------------------------------
        | textContent is intentional
        |--------------------------------------------------------------------------
        |
        | We don't use innerHTML for AI/user messages.
        | This prevents HTML returned by the AI from being
        | interpreted as actual HTML.
        |
        */

        bubble.textContent = message;


        wrapper.appendChild(bubble);

        messages.appendChild(wrapper);


        messages.scrollTop =
            messages.scrollHeight;


        /*
        |--------------------------------------------------------------------------
        | Save immediately after adding the message
        |--------------------------------------------------------------------------
        */

        saveChat();
    }


    /*
    |--------------------------------------------------------------------------
    | Send Message
    |--------------------------------------------------------------------------
    */

    async function sendMessage(message) {

        if (!message.trim()) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Add user's message
        |--------------------------------------------------------------------------
        */

        addMessage(
            message,
            'user'
        );


        input.value = '';

        sendButton.disabled = true;


        /*
        |--------------------------------------------------------------------------
        | Typing indicator
        |--------------------------------------------------------------------------
        */

        const typing =
            document.createElement('div');

        typing.className =
            'chat-message ai';


        typing.innerHTML =
            '<div class="chat-bubble typing">AI is thinking...</div>';


        messages.appendChild(typing);


        messages.scrollTop =
            messages.scrollHeight;


        try {

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
                                document.querySelector(
                                    'input[name="_token"]'
                                ).value
                        },

                        body: JSON.stringify({

                            message: message

                        })
                    }
                );


            const data =
                await response.json();


            /*
            |--------------------------------------------------------------------------
            | Remove typing indicator
            |--------------------------------------------------------------------------
            */

            typing.remove();


            /*
            |--------------------------------------------------------------------------
            | AI Response
            |--------------------------------------------------------------------------
            */

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


        } catch (error) {

            typing.remove();


            addMessage(
                'Sorry, I could not connect to the AI assistant right now.',
                'ai'
            );


        } finally {

            sendButton.disabled = false;

            input.focus();

        }
    }


    /*
    |--------------------------------------------------------------------------
    | Form Submit
    |--------------------------------------------------------------------------
    */

    form.addEventListener(
        'submit',
        function (event) {

            event.preventDefault();

            sendMessage(
                input.value
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Suggestion Buttons
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll('.suggestion-button')
        .forEach(
            function (button) {

                button.addEventListener(
                    'click',
                    function () {

                        sendMessage(
                            button.dataset.message
                        );

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Load Previous Conversation
    |--------------------------------------------------------------------------
    */

    loadChat();

});
</script>

@endsection
