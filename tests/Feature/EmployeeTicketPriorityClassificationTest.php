<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\TicketCategory;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class EmployeeTicketPriorityClassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_creation_uses_ai_priority_when_priority_is_omitted(): void
    {
        config(['services.openai.api_key' => 'test-key']);
        [$user, $category, $manualPriority, $aiPriority] = $this->createTicketDependencies();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"priority":"High"}'],
                ]],
            ]),
        ]);

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->ticketPayload($category))
            ->assertRedirect(route('CreateTicket'));

        $this->assertDatabaseHas('Tickets', [
            'CategoryId' => $category->Id,
            'PriorityId' => $aiPriority->Id,
        ]);
        Http::assertSentCount(1);
    }

    public function test_ticket_creation_preserves_a_manually_selected_priority(): void
    {
        [$user, $category, $manualPriority] = $this->createTicketDependencies();

        Http::fake();

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->ticketPayload($category, $manualPriority))
            ->assertRedirect(route('CreateTicket'));

        $this->assertDatabaseHas('Tickets', [
            'CategoryId' => $category->Id,
            'PriorityId' => $manualPriority->Id,
        ]);
        Http::assertNothingSent();
    }

    public function test_ticket_creation_rejects_an_invalid_ai_priority(): void
    {
        config(['services.openai.api_key' => 'test-key']);
        [$user, $category] = $this->createTicketDependencies();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"priority":"Critical"}'],
                ]],
            ]),
        ]);

        $this->actingAs($user)
            ->from(route('CreateTicket'))
            ->post(route('tickets.store'), $this->ticketPayload($category))
            ->assertRedirect(route('CreateTicket'))
            ->assertSessionHasErrors('PriorityId');

        $this->assertDatabaseMissing('Tickets', ['Title' => 'Cannot access VPN']);
    }

    public function test_ticket_creation_requires_manual_priority_when_openai_fails(): void
    {
        config(['services.openai.api_key' => 'test-key']);
        [$user, $category] = $this->createTicketDependencies();

        Http::fake(['api.openai.com/*' => Http::response([], 500)]);

        $this->actingAs($user)
            ->from(route('CreateTicket'))
            ->post(route('tickets.store'), $this->ticketPayload($category))
            ->assertRedirect(route('CreateTicket'))
            ->assertSessionHasErrors('PriorityId');

        $this->assertDatabaseMissing('Tickets', ['Title' => 'Cannot access VPN']);
    }

    public function test_ticket_creation_requires_manual_priority_when_api_key_is_missing(): void
    {
        config(['services.openai.api_key' => null]);
        [$user, $category] = $this->createTicketDependencies();

        $this->actingAs($user)
            ->from(route('CreateTicket'))
            ->post(route('tickets.store'), $this->ticketPayload($category))
            ->assertRedirect(route('CreateTicket'))
            ->assertSessionHasErrors('PriorityId');

        $this->assertDatabaseMissing('Tickets', ['Title' => 'Cannot access VPN']);
    }

    /** @return array{User, TicketCategory, TicketPriority, TicketPriority} */
    private function createTicketDependencies(): array
    {
        $role = Role::create(['Name' => 'Employee']);
        $user = User::create([
            'RoleId' => $role->Id,
            'Name' => 'Test Employee',
            'Email' => 'employee@example.test',
            'Password' => 'password',
            'Department' => 'IT',
            'IsActive' => true,
        ]);

        TicketStatus::create(['Name' => 'Open']);
        $category = TicketCategory::create(['Name' => 'Network', 'IsActive' => true]);

        $manualPriority = $this->priority('Medium', 2);
        $aiPriority = $this->priority('High', 3);

        return [$user, $category, $manualPriority, $aiPriority];
    }

    private function priority(string $name, int $level): TicketPriority
    {
        $priority = new TicketPriority();
        $priority->Name = $name;
        $priority->Level = $level;
        $priority->save();

        return $priority;
    }

    private function ticketPayload(TicketCategory $category, ?TicketPriority $priority = null): array
    {
        return array_filter([
            'Title' => 'Cannot access VPN',
            'CategoryId' => $category->Id,
            'PriorityId' => $priority?->Id,
            'Description' => 'The VPN connection fails for the employee.',
        ], static fn ($value) => $value !== null);
    }
}
