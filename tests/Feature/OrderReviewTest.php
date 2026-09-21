<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderReviewTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected User $seller;

    protected Shop $shop;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);

        $this->customer = User::factory()->customer()->create();
        $this->seller = User::factory()->seller()->create();
        $this->shop = Shop::factory()->create(['user_id' => $this->seller->id]);
        $category = Category::factory()->create();

        $this->product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $category->id,
            'name' => 'Produk Keren Indonesia',
            'stock' => 20,
            'price' => 50000,
        ]);
    }

    private function createSampleOrder(string $status = 'pending'): Order
    {
        $order = Order::create([
            'order_number' => 'ORD-'.strtoupper(uniqid()),
            'user_id' => $this->customer->id,
            'status' => $status,
            'subtotal' => 100000,
            'discount_amount' => 0,
            'shipping_cost' => 10000,
            'grand_total' => 110000,
            'shipping_address' => [
                'recipient_name' => 'Budi Santoso',
                'phone' => '081234567890',
                'full_address' => 'Jl. Sudirman No 1',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
            ],
            'shipping_courier' => 'JNE Reguler',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'shop_id' => $this->shop->id,
            'product_name' => $this->product->name,
            'price' => 50000,
            'quantity' => 2,
            'subtotal' => 100000,
        ]);

        Payment::create([
            'payment_number' => 'PAY-'.strtoupper(uniqid()),
            'order_id' => $order->id,
            'payment_method' => 'bank_transfer',
            'amount' => 110000,
            'status' => $status === 'paid' || $status === 'completed' ? 'paid' : 'pending',
        ]);

        return $order;
    }

    public function test_customer_can_view_order_list(): void
    {
        $this->createSampleOrder('pending');

        $response = $this->actingAs($this->customer)->get(route('customer.orders.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Pesanan Saya');
        $response->assertSee('Produk Keren Indonesia');
    }

    public function test_customer_can_view_order_detail(): void
    {
        $order = $this->createSampleOrder('pending');

        $response = $this->actingAs($this->customer)->get(route('orders.show', $order->id));

        $response->assertStatus(200);
        $response->assertSee($order->order_number);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Simulasi Bayar Instan');
    }

    public function test_customer_can_pay_pending_order(): void
    {
        $order = $this->createSampleOrder('pending');

        $response = $this->actingAs($this->customer)->post(route('orders.pay', $order->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
        ]);
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'paid',
        ]);
    }

    public function test_customer_can_cancel_pending_order_and_stock_is_restored(): void
    {
        $initialStock = $this->product->stock;
        $order = $this->createSampleOrder('pending');

        $response = $this->actingAs($this->customer)->post(route('orders.cancel', $order->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'cancelled',
        ]);
        // Stock incremented by 2
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'stock' => $initialStock + 2,
        ]);
    }

    public function test_customer_cannot_cancel_already_paid_or_completed_order(): void
    {
        $order = $this->createSampleOrder('paid');

        $response = $this->actingAs($this->customer)->post(route('orders.cancel', $order->id));

        $response->assertStatus(403);
    }

    public function test_customer_can_confirm_delivered_order(): void
    {
        $order = $this->createSampleOrder('shipped');

        $response = $this->actingAs($this->customer)->post(route('orders.confirm-delivered', $order->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'completed',
        ]);
    }

    public function test_customer_can_submit_review_for_completed_order_item(): void
    {
        $order = $this->createSampleOrder('completed');
        $item = $order->items->first();

        $response = $this->actingAs($this->customer)->post(route('reviews.store'), [
            'order_item_id' => $item->id,
            'rating' => 5,
            'comment' => 'Produk sangat memuaskan, pengiriman cepat!',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reviews', [
            'order_item_id' => $item->id,
            'user_id' => $this->customer->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Produk sangat memuaskan, pengiriman cepat!',
        ]);

        $this->assertDatabaseHas('order_items', [
            'id' => $item->id,
            'is_reviewed' => true,
        ]);

        // Product rating average recalculated
        $this->assertDatabaseHas('products', [
            'id' => $this->product->id,
            'rating_avg' => 5.00,
            'reviews_count' => 1,
        ]);
    }

    public function test_unauthorized_user_cannot_view_other_user_order(): void
    {
        $order = $this->createSampleOrder('pending');
        $otherUser = User::factory()->customer()->create();

        $response = $this->actingAs($otherUser)->get(route('orders.show', $order->id));

        $response->assertStatus(403);
    }
}
