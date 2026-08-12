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
            Log::error('OpenAI API key is missing.');

            return response()->json([
                'success' => false,
                'message' => 'The AI assistant is currently unavailable.',
            ], 503);
        }

        /*
         * Get existing conversation.
         */
        $conversation = session('ai_chat_messages', []);

        /*
         * Add employee message.
         */
        $conversation[] = [
            'role' => 'user',
            'content' => $validated['message'],
        ];

        /*
         * Keep conversation reasonably small.
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

            /*
             * ---------------------------------------------------------
             * OPENAI REQUEST
             * ---------------------------------------------------------
             *
             * Retry temporary failures such as:
             * 429 rate limit
             * 500 server error
             * 502 bad gateway
             * 503 unavailable
             * 504 gateway timeout
             */

            $maxAttempts = 3;

            $response = null;

            for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {

                try {

                    Log::info('AI Help Desk request started.', [
                        'attempt' => $attempt,
                    ]);

                    $response = Http::acceptJson()
                        ->withToken($apiKey)
                        ->connectTimeout(10)
                        ->timeout(60)
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

                    /*
                     * Successful response.
                     */
                    if ($response->successful()) {
                        break;
                    }

                    /*
                     * Get useful information for the Laravel log.
                     */
                    Log::warning('OpenAI API returned an error.', [
                        'attempt' => $attempt,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    /*
                     * Retry only temporary errors.
                     */
                    $retryableStatuses = [
                        429,
                        500,
                        502,
                        503,
                        504,
                    ];

                    if (
                        !in_array(
                            $response->status(),
                            $retryableStatuses,
                            true
                        )
                    ) {
                        break;
                    }

                    /*
                     * Wait before retrying.
                     */
                    if ($attempt < $maxAttempts) {
                        sleep($attempt);
                    }

                } catch (Throwable $exception) {

                    Log::warning('OpenAI connection attempt failed.', [
                        'attempt' => $attempt,
                        'exception' => $exception->getMessage(),
                    ]);

                    if ($attempt < $maxAttempts) {
                        sleep($attempt);
                    }
                }
            }

            /*
             * If no response was received at all.
             */
            if (!$response) {

                array_pop($conversation);

                return response()->json([
                    'success' => false,
                    'message' =>
                        'The AI assistant could not connect right now. Please try again.',
                ], 503);
            }

            /*
             * OpenAI returned an error after all attempts.
             */
            if ($response->failed()) {

                Log::error('OpenAI request failed after retries.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                array_pop($conversation);

                /*
                 * Give the frontend a more useful message.
                 */
                if ($response->status() === 429) {

                    $message =
                        'The AI service is temporarily busy. Please try again in a few seconds.';

                } elseif ($response->status() >= 500) {

                    $message =
                        'The AI service is temporarily unavailable. Please try again.';

                } else {

                    $message =
                        'The AI assistant could not process your request.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 502);
            }

            /*
             * Extract AI response.
             */
            $content = data_get(
                $response->json(),
                'choices.0.message.content'
            );

            /*
             * Make sure we actually received text.
             */
            if (!is_string($content) || blank($content)) {

                Log::error('OpenAI returned an empty response.', [
                    'response' => $response->json(),
                ]);

                array_pop($conversation);

                return response()->json([
                    'success' => false,
                    'message' =>
                        'The AI assistant returned an empty response. Please try again.',
                ], 502);
            }

            /*
             * Save assistant response.
             */
            $conversation[] = [
                'role' => 'assistant',
                'content' => $content,
            ];

            /*
             * Keep latest 20 messages.
             */
            $conversation = array_slice(
                $conversation,
                -20
            );

            /*
             * Save conversation.
             */
            session([
                'ai_chat_messages' => $conversation,
            ]);

            /*
             * Return successful response.
             */
            return response()->json([
                'success' => true,
                'message' => $content,
            ]);

        } catch (Throwable $exception) {

            Log::error(
                'AI Help Desk unexpected error.',
                [
                    'exception' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]
            );

            array_pop($conversation);

            return response()->json([
                'success' => false,
                'message' =>
                    'The AI assistant is temporarily unavailable. Please try again.',
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
