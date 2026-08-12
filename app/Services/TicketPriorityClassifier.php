<?php

namespace App\Services;

use App\Models\TicketPriority;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TicketPriorityClassifier
{
    /** Return only a priority that currently exists in TicketPriorities. */
    public function classify(string $title, string $description): ?TicketPriority
    {
        $apiKey = config('services.openai.api_key');

        if (blank($apiKey)) {
            return null;
        }

        $priorities = TicketPriority::query()
            ->orderBy('Level')
            ->get(['Id', 'Name', 'Level', 'Description']);

        if ($priorities->isEmpty()) {
            return null;
        }

        try {
            $response = Http::acceptJson()
                ->withToken($apiKey)
                ->connectTimeout(5)
                ->timeout((int) config('services.openai.timeout', 10))
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => config('services.openai.model'),
                    'temperature' => 0,
                    'response_format' => [
                        'type' => 'json_schema',
                        'json_schema' => [
                            'name' => 'ticket_priority',
                            'strict' => true,
                            'schema' => [
                                'type' => 'object',
                                'properties' => ['priority' => ['type' => 'string']],
                                'required' => ['priority'],
                                'additionalProperties' => false,
                            ],
                        ],
                    ],
                    'messages' => [
                        ['role' => 'system', 'content' => 'Classify IT help-desk ticket urgency and impact. Return JSON only. The priority must exactly match one of the supplied priority names.'],
                        ['role' => 'user', 'content' => json_encode([
                            'ticket' => ['title' => $title, 'description' => $description],
                            'priorities' => $priorities->map(fn (TicketPriority $priority) => [
                                'name' => $priority->Name,
                                'level' => $priority->Level,
                                'description' => $priority->Description,
                            ])->values(),
                        ], JSON_THROW_ON_ERROR)],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('OpenAI ticket-priority classification request failed.', ['status' => $response->status()]);
                return null;
            }

            $content = data_get($response->json(), 'choices.0.message.content');

            if (! is_string($content)) {
                return null;
            }

            $result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            $priorityName = $result['priority'] ?? null;

            if (! is_string($priorityName) || blank($priorityName)) {
                return null;
            }

            return $this->matchPriority($priorities, $priorityName);
        } catch (Throwable $exception) {
            Log::warning('OpenAI ticket-priority classification was unavailable.', ['exception' => $exception->getMessage()]);
            return null;
        }
    }

    private function matchPriority(Collection $priorities, string $name): ?TicketPriority
    {
        $normalizedName = mb_strtolower(trim($name));

        return $priorities->first(
            fn (TicketPriority $priority) => mb_strtolower(trim($priority->Name)) === $normalizedName
        );
    }
}
