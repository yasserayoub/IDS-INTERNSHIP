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

class EmployeeTicketCategoryClassificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_creation_preserves_a_manually_selected_category(): void
    {
        [$user, $selectedCategory, $aiCategory, $priority] = $this->createTicketDependencies();

        Http::fake();

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->ticketPayload($selectedCategory, $priority))
            ->assertRedirect(route('CreateTicket'));

        $this->assertDatabaseHas('Tickets', [
            'CategoryId' => $selectedCategory->Id,
            'PriorityId' => $priority->Id,
            'Title' => 'Cannot connect to VPN',
        ]);

        $this->assertDatabaseMissing('Tickets', [
            'CategoryId' => $aiCategory->Id,
            'Title' => 'Cannot connect to VPN',
        ]);
        Http::assertNothingSent();
    }

    public function test_ticket_creation_uses_the_ai_category_when_category_is_omitted(): void
    {
        config(['services.openai.api_key' => 'test-key']);

        [$user, $selectedCategory, $aiCategory, $priority] = $this->createTicketDependencies();

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [[
                    'message' => ['content' => '{"category":"Network"}'],
                ]],
            ]),
        ]);

        $this->actingAs($user)
            ->post(route('tickets.store'), $this->ticketPayload(null, $priority))
            ->assertRedirect(route('CreateTicket'));

        $this->assertDatabaseHas('Tickets', [
            'CategoryId' => $aiCategory->Id,
            'PriorityId' => $priority->Id,
            'Title' => 'Cannot connect to VPN',
        ]);
        Http::assertSentCount(1);
    }

    public function test_ticket_creation_requires_manual_category_when_ai_is_unavailable(): void
    {
        config(['services.openai.api_key' => null]);

        [$user, $selectedCategory, $aiCategory, $priority] = $this->createTicketDependencies();

        $this->actingAs($user)
            ->from(route('CreateTicket'))
            ->post(route('tickets.store'), $this->ticketPayload(null, $priority))
            ->assertRedirect(route('CreateTicket'))
            ->assertSessionHasErrors('CategoryId');

        $this->assertDatabaseMissing('Tickets', [
            'Title' => 'Cannot connect to VPN',
        ]);
    }

    /** @return array{User, TicketCategory, TicketCategory, TicketPriority} */
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

        $selectedCategory = TicketCategory::create([
            'Name' => 'Hardware',
            'IsActive' => true,
        ]);

        $aiCategory = TicketCategory::create([
            'Name' => 'Network',
            'IsActive' => true,
        ]);

        $priority = new TicketPriority();
        $priority->Name = 'Medium';
        $priority->Level = 2;
        $priority->save();

        return [$user, $selectedCategory, $aiCategory, $priority];
    }

    private function ticketPayload(?TicketCategory $category, TicketPriority $priority): array
    {
        return array_filter([
            'Title' => 'Cannot connect to VPN',
            'CategoryId' => $category?->Id,
            'PriorityId' => $priority->Id,
            'Description' => 'The VPN connection fails for the employee.',
        ], static fn ($value) => $value !== null);
    }
}