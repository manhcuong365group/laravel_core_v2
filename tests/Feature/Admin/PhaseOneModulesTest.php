<?php

namespace Tests\Feature\Admin;

use App\Models\Contact;
use App\Models\Order;
use App\Models\Subscriber;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseOneModulesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_editor_is_blocked_from_logs_permission(): void
    {
        $editor = $this->createUserWithRole('editor');

        $response = $this->actingAs($editor)->get(route('backend.logs.index'));

        $response->assertForbidden();
    }

    public function test_order_crud_status_history_and_activity_log_work(): void
    {
        $admin = $this->createUserWithRole('admin');

        $createResponse = $this->actingAs($admin)->post(route('backend.orders.store'), [
            'customer_name' => 'Test Customer',
            'customer_phone' => '0900123456',
            'customer_email' => 'order@example.com',
            'customer_address' => '123 Test Street',
            'shipping_fee' => 5.50,
            'status' => 'new',
            'notes' => 'Customer note',
            'admin_notes' => 'Admin note',
            'items' => [
                [
                    'product_id' => null,
                    'product_name_snapshot' => 'Custom Item',
                    'sku_snapshot' => 'SKU-001',
                    'price' => 10,
                    'quantity' => 2,
                ],
            ],
        ]);

        $createResponse->assertRedirect(route('backend.orders.index', absolute: false));

        $order = Order::query()->first();
        $this->assertNotNull($order);
        $this->assertSame('new', $order->status);
        $this->assertDatabaseCount('order_status_histories', 1);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'orders.create',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
        ]);

        $statusResponse = $this->actingAs($admin)->patch(route('backend.orders.status', $order), [
            'status' => 'completed',
            'note' => 'Done',
        ]);

        $statusResponse->assertSessionHas('success');
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
        $this->assertDatabaseCount('order_status_histories', 2);
        $this->assertDatabaseHas('activity_logs', [
            'action' => 'orders.status',
            'subject_type' => Order::class,
            'subject_id' => $order->id,
        ]);
    }

    public function test_newsletter_crud_export_and_activity_log_work(): void
    {
        $admin = $this->createUserWithRole('admin');

        $this->actingAs($admin)->post(route('backend.newsletters.store'), [
            'email' => 'sub@example.com',
            'name' => 'Subscriber',
            'status' => 'subscribed',
            'source' => 'admin',
        ])->assertRedirect();

        $subscriber = Subscriber::query()->first();
        $this->assertNotNull($subscriber);

        $this->actingAs($admin)->put(route('backend.newsletters.update', $subscriber), [
            'email' => 'sub@example.com',
            'name' => 'Subscriber',
            'status' => 'unsubscribed',
            'source' => 'admin',
        ])->assertRedirect();

        $exportResponse = $this->actingAs($admin)->get(route('backend.newsletters.export'));
        $exportResponse->assertOk();
        $exportResponse->assertHeader('content-type', 'text/csv; charset=UTF-8');

        $this->actingAs($admin)->delete(route('backend.newsletters.destroy', $subscriber))->assertRedirect();

        $this->assertDatabaseHas('activity_logs', ['action' => 'newsletters.create']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'newsletters.update']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'newsletters.export']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'newsletters.delete']);
    }

    public function test_contact_form_and_inbox_actions_write_database_and_logs(): void
    {
        $this->post(route('contact.store'), [
            'name' => 'Contact User',
            'email' => 'contact@example.com',
            'phone' => '0911222333',
            'subject' => 'Need support',
            'message' => 'Please call me back.',
        ])->assertRedirect();

        $contact = Contact::query()->first();
        $this->assertNotNull($contact);
        $this->assertSame('new', $contact->status);
        $this->assertNotEmpty($contact->ip_address);

        $admin = $this->createUserWithRole('admin');

        $this->actingAs($admin)->patch(route('backend.contacts.status', $contact), [
            'status' => 'replied',
        ])->assertRedirect();

        $this->actingAs($admin)->patch(route('backend.contacts.notes', $contact), [
            'admin_notes' => 'Handled by admin',
        ])->assertRedirect();

        $this->actingAs($admin)->delete(route('backend.contacts.destroy', $contact))->assertRedirect(route('backend.contacts.index', absolute: false));

        $this->assertDatabaseHas('activity_logs', ['action' => 'contacts.status']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'contacts.notes']);
        $this->assertDatabaseHas('activity_logs', ['action' => 'contacts.delete']);
    }

    public function test_seeded_permissions_match_phase_one_contract(): void
    {
        $admin = $this->createUserWithRole('admin');
        $editor = $this->createUserWithRole('editor');

        $this->assertTrue($admin->hasPermissionTo('orders.status'));
        $this->assertTrue($admin->hasPermissionTo('newsletters.export'));
        $this->assertTrue($admin->hasPermissionTo('logs.view'));

        $this->assertTrue($editor->hasPermissionTo('orders.view'));
        $this->assertFalse($editor->hasPermissionTo('orders.delete'));
        $this->assertFalse($editor->hasPermissionTo('newsletters.export'));
        $this->assertFalse($editor->hasPermissionTo('logs.view'));
    }

    public function test_bulk_order_actions_work(): void
    {
        $admin = $this->createUserWithRole('admin');

        $orderA = Order::query()->create([
            'code' => 'ORD-TEST-1001',
            'customer_name' => 'A',
            'customer_phone' => '0900000001',
            'status' => 'new',
            'subtotal' => 100,
            'shipping_fee' => 0,
            'total' => 100,
        ]);
        $orderB = Order::query()->create([
            'code' => 'ORD-TEST-1002',
            'customer_name' => 'B',
            'customer_phone' => '0900000002',
            'status' => 'new',
            'subtotal' => 200,
            'shipping_fee' => 0,
            'total' => 200,
        ]);

        $this->actingAs($admin)->patch(route('backend.orders.bulk-status'), [
            'ids' => [$orderA->id, $orderB->id],
            'status' => 'completed',
            'note' => 'bulk test',
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('orders', ['id' => $orderA->id, 'status' => 'completed']);
        $this->assertDatabaseHas('orders', ['id' => $orderB->id, 'status' => 'completed']);

        $this->actingAs($admin)->post(route('backend.orders.bulk-delete'), [
            '_method' => 'DELETE',
            'ids' => [$orderA->id, $orderB->id],
        ])->assertSessionHas('success');

        $this->assertSoftDeleted('orders', ['id' => $orderA->id]);
        $this->assertSoftDeleted('orders', ['id' => $orderB->id]);
    }

    public function test_bulk_user_actions_work(): void
    {
        $admin = $this->createUserWithRole('admin');
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($admin)->patch(route('backend.users.bulk-status'), [
            'ids' => [$userA->id, $userB->id],
            'is_active' => false,
        ])->assertSessionHas('success');

        $this->assertDatabaseHas('users', ['id' => $userA->id, 'is_active' => 0]);
        $this->assertDatabaseHas('users', ['id' => $userB->id, 'is_active' => 0]);

        $this->actingAs($admin)->post(route('backend.users.bulk-delete'), [
            '_method' => 'DELETE',
            'ids' => [$userA->id, $userB->id],
        ])->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $userA->id]);
        $this->assertDatabaseMissing('users', ['id' => $userB->id]);
    }

    public function test_dashboard_mini_stats_endpoint_returns_json(): void
    {
        $admin = $this->createUserWithRole('admin');

        $this->actingAs($admin)
            ->get(route('backend.dashboard.mini-stats'))
            ->assertOk()
            ->assertJsonStructure([
                'new_orders',
                'new_contacts',
                'order_processing_rate',
                'contact_processing_rate',
                'generated_at',
            ]);
    }

    protected function createUserWithRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        return $user;
    }
}

