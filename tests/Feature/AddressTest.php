<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddressTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    }

    public function test_user_can_view_address_page(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->get(route('customer.addresses.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Alamat Pengiriman');
    }

    public function test_user_can_store_new_address(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('customer.addresses.store'), [
            'recipient_name' => 'Budi Santoso',
            'phone' => '08123456789',
            'label' => 'Rumah',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12160',
            'address_line' => 'Jl. Sudirman No. 123 RT 01 RW 02',
            'is_primary' => '1',
        ]);

        $response->assertRedirect(route('customer.addresses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi Santoso',
            'phone' => '08123456789',
            'label' => 'Rumah',
            'city' => 'Jakarta Selatan',
            'postal_code' => '12160',
            'is_primary' => true,
        ]);
    }

    public function test_storing_address_requires_mandatory_fields(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('customer.addresses.store'), []);

        $response->assertSessionHasErrors([
            'recipient_name',
            'phone',
            'label',
            'province',
            'city',
            'district',
            'postal_code',
            'address_line',
        ]);
    }

    public function test_user_can_update_address(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'recipient_name' => 'Nama Lama',
            'is_primary' => false,
        ]);

        $response = $this->actingAs($user)->put(route('customer.addresses.update', $address->id), [
            'recipient_name' => 'Nama Baru',
            'phone' => '08987654321',
            'label' => 'Kantor',
            'province' => 'Jawa Barat',
            'city' => 'Bandung',
            'district' => 'Coblong',
            'postal_code' => '40132',
            'address_line' => 'Jl. Dago No. 10',
            'is_primary' => '1',
        ]);

        $response->assertRedirect(route('customer.addresses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'id' => $address->id,
            'recipient_name' => 'Nama Baru',
            'label' => 'Kantor',
            'city' => 'Bandung',
            'is_primary' => true,
        ]);
    }

    public function test_user_can_set_address_as_primary(): void
    {
        $user = User::factory()->customer()->create();
        $address1 = Address::factory()->create(['user_id' => $user->id, 'is_primary' => true]);
        $address2 = Address::factory()->create(['user_id' => $user->id, 'is_primary' => false]);

        $response = $this->actingAs($user)->post(route('customer.addresses.primary', $address2->id));

        $response->assertRedirect(route('customer.addresses.index'));
        $this->assertTrue($address2->fresh()->is_primary);
        $this->assertFalse($address1->fresh()->is_primary);
    }

    public function test_user_can_delete_address(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->delete(route('customer.addresses.destroy', $address->id));

        $response->assertRedirect(route('customer.addresses.index'));
        $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
    }

    public function test_user_can_store_address_from_checkout_and_redirect_back_to_checkout(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('customer.addresses.store'), [
            'recipient_name' => 'Budi Santoso',
            'phone' => '08123456789',
            'label' => 'Rumah',
            'province' => 'DKI Jakarta',
            'city' => 'Jakarta Selatan',
            'district' => 'Kebayoran Baru',
            'postal_code' => '12160',
            'address_line' => 'Jl. Sudirman No. 123 RT 01 RW 02',
            'is_primary' => '1',
            'redirect' => 'checkout',
        ]);

        $response->assertRedirect(route('checkout.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('addresses', [
            'user_id' => $user->id,
            'recipient_name' => 'Budi Santoso',
            'is_primary' => true,
        ]);
    }
}
