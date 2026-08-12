<?php

namespace Tests\Feature;

use App\Models\TicketCategory;
use App\Services\TicketCategoryClassifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TicketCategoryClassifierTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_maps_an_ai_category_to_an_existing_ticket_category(): void
    {
        config(['services.openai.api_key' => 'test-key']);

        $network = new TicketCategory();
        $network->Name = 'Network';
        $network->Description = 'Internet, Wi-Fi, VPN, and connectivity issues.';
        $network->IsActive = true;
        $network->save();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"category":"Network"}'],
                ]],
            ]),
        ]);

        $category = app(TicketCategoryClassifier::class)->classify(
            'Laptop cannot connect to Wi-Fi',
            'The office wireless network is not available.'
        );

        $this->assertNotNull($category);
        $this->assertSame($network->Id, $category->Id);
    }

    public function test_it_returns_null_when_ai_suggests_a_category_not_in_the_database(): void
    {
        config(['services.openai.api_key' => 'test-key']);

        $category = new TicketCategory();
        $category->Name = 'Network';
        $category->IsActive = true;
        $category->save();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"category":"Hardware"}'],
                ]],
            ]),
        ]);

        $result = app(TicketCategoryClassifier::class)->classify(
            'Computer will not start',
            'The device shows no sign of power.'
        );

        $this->assertNull($result);
    }
}
