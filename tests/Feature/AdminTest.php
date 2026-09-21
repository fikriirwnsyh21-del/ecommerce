<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected User $seller;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);

        $this->admin = User::factory()->admin()->create();
        $this->customer = User::factory()->customer()->create();
        $this->seller = User::factory()->seller()->create();
    }

    public function test_admin_can_view_dashboard(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Kontrol');
        $response->assertSee('Total Nilai GMV');
    }

    public function test_customer_cannot_access_admin_dashboard(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_list_users_and_toggle_status(): void
    {
        $targetUser = User::factory()->customer()->create(['is_active' => true]);

        $responseList = $this->actingAs($this->admin)->get(route('admin.users.index'));
        $responseList->assertStatus(200);
        $responseList->assertSee($targetUser->name);

        // Toggle to suspend
        $responseToggle = $this->actingAs($this->admin)->post(route('admin.users.toggle-status', $targetUser->id));
        $responseToggle->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'is_active' => false,
        ]);
    }

    public function test_admin_cannot_suspend_self(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.users.toggle-status', $this->admin->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'is_active' => true,
        ]);
    }

    public function test_admin_can_create_and_delete_category(): void
    {
        $responseStore = $this->actingAs($this->admin)->post(route('admin.categories.store'), [
            'name' => 'Kategori Baru Keren',
            'icon' => 'sparkles',
            'description' => 'Deskripsi kategori pengujian.',
            'sort_order' => 1,
        ]);

        $responseStore->assertRedirect();
        $this->assertDatabaseHas('categories', [
            'name' => 'Kategori Baru Keren',
            'slug' => 'kategori-baru-keren',
        ]);

        $cat = Category::where('slug', 'kategori-baru-keren')->first();
        $responseDelete = $this->actingAs($this->admin)->delete(route('admin.categories.destroy', $cat->id));
        $responseDelete->assertRedirect();
        $this->assertDatabaseMissing('categories', ['id' => $cat->id]);
    }

    public function test_admin_can_toggle_product_status_and_delete_for_moderation(): void
    {
        $shop = Shop::factory()->create(['user_id' => $this->seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Produk Ilegal / Pelanggaran',
            'is_active' => true,
        ]);

        // Toggle status (take down)
        $respToggle = $this->actingAs($this->admin)->post(route('admin.products.toggle-status', $product->id));
        $respToggle->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => false,
        ]);

        // Force delete
        $respDelete = $this->actingAs($this->admin)->delete(route('admin.products.destroy', $product->id));
        $respDelete->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_can_inspect_orders(): void
    {
        $order = Order::create([
            'order_number' => 'ORD-ADMIN-CHECK',
            'user_id' => $this->customer->id,
            'status' => 'paid',
            'subtotal' => 200000,
            'discount_amount' => 0,
            'shipping_cost' => 15000,
            'grand_total' => 215000,
            'shipping_address' => [
                'recipient_name' => 'Joko',
                'phone' => '08111222333',
                'full_address' => 'Jl MH Thamrin',
            ],
            'shipping_courier' => 'JNE OKE',
        ]);

        Payment::create([
            'payment_number' => 'PAY-ADMIN-CHECK',
            'order_id' => $order->id,
            'payment_method' => 'bank_transfer',
            'amount' => 215000,
            'status' => 'paid',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertSee('ORD-ADMIN-CHECK');
        $response->assertSee('Joko');
        $response->assertSee('Rp 215.000');
    }

    public function test_admin_can_create_and_toggle_voucher(): void
    {
        $responseStore = $this->actingAs($this->admin)->post(route('admin.marketing.vouchers.store'), [
            'code' => 'ADMINPROMO2026',
            'name' => 'Promo Spesial Admin',
            'type' => 'fixed',
            'amount' => 20000,
            'min_purchase' => 100000,
            'quota' => 50,
            'start_date' => now()->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(14)->format('Y-m-d H:i:s'),
        ]);

        $responseStore->assertRedirect();
        $this->assertDatabaseHas('vouchers', [
            'code' => 'ADMINPROMO2026',
            'is_active' => true,
        ]);

        $voucher = Voucher::where('code', 'ADMINPROMO2026')->first();
        $responseToggle = $this->actingAs($this->admin)->post(route('admin.marketing.vouchers.toggle', $voucher->id));
        $responseToggle->assertRedirect();
        $this->assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'is_active' => false,
        ]);
    }
}
