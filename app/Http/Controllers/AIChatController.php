<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class AIChatController extends Controller
{
    /**
     * Display the AI Help Desk assistant.
     */
    public function index()
    {
        return view('ai-chat.index');
    }

    /**
     * Return the current user's saved AI conversation.
     */
    public function history(Request $request)
    {
        return response()->json([
            'success' => true,
            'messages' => session('ai_chat_messages', []),
        ]);
    }

    /**
     * Send a message to the AI assistant.
     */
    public function chat(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $apiKey = config('services.openai.api_key');

        if (blank($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'The AI assistant is currently unavailable.',
            ], 503);
        }

        /*
         * Get the existing conversation from the session.
         */
        $conversation = session('ai_chat_messages', []);

        /*
         * Add the new employee message.
         */
        $conversation[] = [
            'role' => 'user',
            'content' => $validated['message'],
        ];

        /*
         * Keep the conversation from becoming unnecessarily large.
         *
         * We keep the most recent 20 messages.
         */
        $conversation = array_slice($conversation, -20);

        try {

            $messages = array_merge(
                [
                    [
                        'role' => 'system',
                        'content' => <<<'PROMPT'
You are the IT Help Desk AI Assistant for an internal IT support system.

Your job is to help employees with common IT problems in a clear, friendly,
and practical way.

You can help with:
- Computers and laptops
- Wi-Fi and network problems
- VPN problems
- Printers
- Email problems
- Password and account problems
- Software problems
- Basic troubleshooting

Give simple step-by-step troubleshooting instructions when appropriate.

If the employee describes a problem that appears to require IT support,
explain that they can create a support ticket.

Do not claim that you performed an action you cannot actually perform.

Do not invent company policies, ticket information, users, or database records.

Keep responses concise and useful.
PROMPT,
                    ],
                ],
                $conversation
            );

            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->connectTimeout(5)
                ->timeout((int) config('services.openai.timeout', 10))
                ->post(
                    'https://api.openai.com/v1/chat/completions',
                    [
                        'model' => config(
                            'services.openai.model',
                            'gpt-4o-mini'
                        ),

                        'temperature' => 0.3,

                        'messages' => $messages,
                    ]
                );


            if ($response->failed()) {

                Log::warning(
                    'AI Help Desk request failed.',
                    [
                        'status' => $response->status(),
                    ]
                );

                /*
                 * Remove the user message we added because
                 * the AI request failed.
                 */
                array_pop($conversation);

                return response()->json([
                    'success' => false,
                    'message' =>
                        'The AI assistant could not process your request right now.',
                ], 502);
            }


            $content = data_get(
                $response->json(),
                'choices.0.message.content'
            );


            if (!is_string($content) || blank($content)) {

                array_pop($conversation);

                return response()->json([
                    'success' => false,
                    'message' =>
                        'The AI assistant returned an empty response.',
                ], 502);
            }


            /*
             * Save the AI response.
             */
            $conversation[] = [
                'role' => 'assistant',
                'content' => $content,
            ];


            /*
             * Keep only the latest 20 messages.
             */
            $conversation = array_slice(
                $conversation,
                -20
            );


            /*
             * Save conversation in Laravel session.
             */
            session([
                'ai_chat_messages' => $conversation,
            ]);


            return response()->json([
                'success' => true,
                'message' => $content,
            ]);


        } catch (Throwable $exception) {

            Log::warning(
                'AI Help Desk request unavailable.',
                [
                    'exception' => $exception->getMessage(),
                ]
            );

            /*
             * Remove unsaved user message if the request failed.
             */
            array_pop($conversation);

            return response()->json([
                'success' => false,
                'message' =>
                    'The AI assistant is temporarily unavailable.',
            ], 503);
        }
    }


    /**
     * Clear the current user's AI conversation.
     */
    public function clearHistory(Request $request)
    {
        session()->forget('ai_chat_messages');

        return response()->json([
            'success' => true,
            'message' => 'Conversation cleared.',
        ]);
    }
}
