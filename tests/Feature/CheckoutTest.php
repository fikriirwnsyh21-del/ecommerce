<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
    }

    public function test_user_can_view_checkout_page_with_selected_cart_items(): void
    {
        $user = User::factory()->customer()->create();
        Address::factory()->create(['user_id' => $user->id, 'is_primary' => true]);

        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Smart LED Strip RGB',
            'stock' => 10,
        ]);

        $cartService = new CartService;
        $cartService->addItem($user, $product->id, null, 1);

        $response = $this->actingAs($user)->get(route('checkout.index'));

        $response->assertStatus(200);
        $response->assertSee('Checkout Pesanan');
        $response->assertSee('Smart LED Strip RGB');
    }

    public function test_user_can_complete_checkout_and_create_order(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create(['user_id' => $user->id, 'is_primary' => true]);

        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'price' => 250000,
            'stock' => 5,
        ]);

        $cartService = new CartService;
        $cartService->addItem($user, $product->id, null, 1);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'address_id' => $address->id,
            'shipping_courier' => 'Reguler (J&T Express - 2-3 Hari)',
            'payment_method' => 'Virtual Account',
            'payment_channel' => 'BCA Virtual Account',
            'notes' => 'Tolong kirim siang hari',
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'subtotal' => 250000,
            'shipping_cost' => 15000,
            'grand_total' => 265000,
            'status' => 'pending',
        ]);

        $response->assertRedirect();
    }

    public function test_user_can_checkout_with_qris_and_view_realistic_qris_payment_page(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create(['user_id' => $user->id, 'is_primary' => true]);

        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'price' => 150000,
            'stock' => 10,
        ]);

        $cartService = new CartService;
        $cartService->addItem($user, $product->id, null, 1);

        $response = $this->actingAs($user)->post(route('checkout.process'), [
            'address_id' => $address->id,
            'shipping_courier' => 'Reguler (J&T Express - 2-3 Hari)',
            'payment_method' => 'QRIS (Semua Pembayaran)',
            'payment_channel' => 'QRIS',
        ]);

        $response->assertRedirect();
        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $this->assertTrue($order->payment->is_qris);
        $this->assertStringContainsString('api.qrserver.com', $order->payment->qris_qr_url);

        // View payment detail page
        $detailResponse = $this->actingAs($user)->get(route('orders.show', $order->id));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('KEBUTUHAN GAMING GEN Z OFFICIAL STORE');
        $detailResponse->assertSee('Pembayaran Nasional');
        $detailResponse->assertSee('Pindai kode QRIS di atas untuk membayar');
        $detailResponse->assertSee('Simulasi Bayar Instan');
    }
}
