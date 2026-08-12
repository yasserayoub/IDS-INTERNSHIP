<?php

namespace Tests\Feature;

use App\Models\TicketPriority;
use App\Services\TicketPriorityClassifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TicketPriorityClassifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_maps_an_ai_priority_to_an_existing_ticket_priority(): void
    {
        config(['services.openai.api_key' => 'test-key']);

        $high = new TicketPriority();
        $high->Name = 'High';
        $high->Level = 3;
        $high->Description = 'A significant issue affecting a user or team.';
        $high->save();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"priority":"High"}'],
                ]],
            ]),
        ]);

        $priority = app(TicketPriorityClassifier::class)->classify(
            'VPN is unavailable for the finance team',
            'Several people cannot connect to the company VPN.'
        );

        $this->assertNotNull($priority);
        $this->assertSame($high->Id, $priority->Id);
    }

    public function test_it_returns_null_when_ai_suggests_a_priority_not_in_ticket_priorities(): void
    {
        config(['services.openai.api_key' => 'test-key']);

        $medium = new TicketPriority();
        $medium->Name = 'Medium';
        $medium->Level = 2;
        $medium->save();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"priority":"Critical"}'],
                ]],
            ]),
        ]);

        $result = app(TicketPriorityClassifier::class)->classify(
            'A service is unavailable',
            'The service cannot be accessed.'
        );

        $this->assertNull($result);
    }

    public function test_it_returns_null_when_openai_is_not_configured(): void
    {
        config(['services.openai.api_key' => null]);

        $result = app(TicketPriorityClassifier::class)->classify(
            'Email is slow',
            'Sending messages is delayed.'
        );

        $this->assertNull($result);
    }
}
