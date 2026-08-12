<?php

namespace App\Services;

use App\Models\TicketCategory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TicketCategoryClassifier
{
    /**
     * Return the AI-selected category only when it is one of the categories
     * currently available in the database. Returning null lets the caller use
     * its existing, user-selected category as a safe fallback.
     */
    public function classify(string $title, string $description): ?TicketCategory
    {
        $apiKey = config('services.openai.api_key');

        if (blank($apiKey)) {
            return null;
        }

        $categories = TicketCategory::query()
            ->where('IsActive', true)
            ->get(['Id', 'Name', 'Description']);

        if ($categories->isEmpty()) {
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
                            'name' => 'ticket_category',
                            'strict' => true,
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'category' => ['type' => 'string'],
                                ],
                                'required' => ['category'],
                                'additionalProperties' => false,
                            ],
                        ],
                    ],
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'Classify IT help-desk tickets. Return JSON only. The category must exactly match one of the supplied category names.',
                        ],
                        [
                            'role' => 'user',
                            'content' => json_encode([
                                'ticket' => [
                                    'title' => $title,
                                    'description' => $description,
                                ],
                                'categories' => $categories->map(fn (TicketCategory $category) => [
                                    'name' => $category->Name,
                                    'description' => $category->Description,
                                ])->values(),
                            ], JSON_THROW_ON_ERROR),
                        ],
                    ],
                ]);

            if ($response->failed()) {
                Log::warning('OpenAI ticket-category classification request failed.', [
                    'status' => $response->status(),
                ]);

                return null;
            }

            $content = data_get($response->json(), 'choices.0.message.content');

            if (! is_string($content)) {
                return null;
            }

            $result = json_decode($content, true, 512, JSON_THROW_ON_ERROR);
            $categoryName = $result['category'] ?? null;

            if (! is_string($categoryName) || blank($categoryName)) {
                return null;
            }

            return $this->matchCategory($categories, $categoryName);
        } catch (Throwable $exception) {
            Log::warning('OpenAI ticket-category classification was unavailable.', [
                'exception' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    private function matchCategory(Collection $categories, string $name): ?TicketCategory
    {
        $normalizedName = mb_strtolower(trim($name));

        return $categories->first(
            fn (TicketCategory $category) => mb_strtolower(trim($category->Name)) === $normalizedName
        );
    }
}
