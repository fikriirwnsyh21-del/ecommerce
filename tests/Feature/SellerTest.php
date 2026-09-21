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

class SellerTest extends TestCase
{
    use RefreshDatabase;

    protected User $seller;

    protected Shop $shop;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);

        $this->seller = User::factory()->seller()->create();
        $this->shop = Shop::factory()->create([
            'user_id' => $this->seller->id,
            'name' => 'Toko Maju Jaya',
            'slug' => 'toko-maju-jaya',
        ]);

        $this->category = Category::factory()->create(['name' => 'Elektronik']);
    }

    public function test_seller_can_view_dashboard_with_metrics(): void
    {
        $response = $this->actingAs($this->seller)->get(route('seller.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Toko Maju Jaya');
        $response->assertSee('Total Pendapatan');
        $response->assertSee('Perlu Diproses');
    }

    public function test_customer_cannot_access_seller_routes(): void
    {
        $customer = User::factory()->customer()->create();

        $response = $this->actingAs($customer)->get(route('seller.dashboard'));

        $response->assertStatus(403);
    }

    public function test_seller_can_list_products(): void
    {
        Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'name' => 'Mechanical Keyboard RGB',
            'price' => 750000,
        ]);

        $response = $this->actingAs($this->seller)->get(route('seller.products.index'));

        $response->assertStatus(200);
        $response->assertSee('Mechanical Keyboard RGB');
    }

    public function test_seller_can_create_new_product_with_variants(): void
    {
        $response = $this->actingAs($this->seller)->post(route('seller.products.store'), [
            'name' => 'Headset Gaming 7.1 Surround',
            'category_id' => $this->category->id,
            'price' => 350000,
            'discount_price' => 299000,
            'stock' => 15,
            'weight' => 450,
            'condition' => 'new',
            'description' => 'Headset gaming dengan kualitas suara jernih dan bass mantap.',
            'variants' => [
                ['name' => 'Hitam', 'price' => 299000, 'stock' => 10],
                ['name' => 'Putih', 'price' => 310000, 'stock' => 5],
            ],
        ]);

        $response->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Headset Gaming 7.1 Surround',
            'shop_id' => $this->shop->id,
            'price' => 350000,
            'discount_price' => 299000,
            'stock' => 15,
        ]);

        $product = Product::where('name', 'Headset Gaming 7.1 Surround')->first();
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'value' => 'Hitam',
            'stock' => 10,
        ]);
        $this->assertDatabaseHas('product_variants', [
            'product_id' => $product->id,
            'value' => 'Putih',
            'stock' => 5,
        ]);
    }

    public function test_seller_can_update_product(): void
    {
        $product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'name' => 'Mouse Wireless Ergonomis',
            'price' => 120000,
            'stock' => 20,
        ]);

        $response = $this->actingAs($this->seller)->put(route('seller.products.update', $product->id), [
            'name' => 'Mouse Wireless Ergonomis Silent Click',
            'category_id' => $this->category->id,
            'price' => 135000,
            'stock' => 30,
            'weight' => 200,
            'condition' => 'new',
            'description' => 'Mouse wireless silent click dengan DPI tinggi.',
            'is_active' => 1,
        ]);

        $response->assertRedirect(route('seller.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Mouse Wireless Ergonomis Silent Click',
            'price' => 135000,
            'stock' => 30,
        ]);
    }

    public function test_seller_cannot_update_another_seller_product(): void
    {
        $otherSeller = User::factory()->seller()->create();
        $otherShop = Shop::factory()->create(['user_id' => $otherSeller->id]);

        $otherProduct = Product::factory()->create([
            'shop_id' => $otherShop->id,
            'category_id' => $this->category->id,
            'name' => 'Produk Toko Lain',
        ]);

        $response = $this->actingAs($this->seller)->put(route('seller.products.update', $otherProduct->id), [
            'name' => 'Hacked Product',
            'category_id' => $this->category->id,
            'price' => 1000,
            'stock' => 1,
            'weight' => 100,
            'condition' => 'new',
            'description' => 'Deskripsi percobaan hacking.',
        ]);

        $response->assertStatus(403);
    }

    public function test_seller_can_delete_product(): void
    {
        $product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
        ]);

        $response = $this->actingAs($this->seller)->delete(route('seller.products.destroy', $product->id));

        $response->assertRedirect(route('seller.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_seller_can_toggle_product_active_status(): void
    {
        $product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->seller)->post(route('seller.products.toggle-active', $product->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'is_active' => false,
        ]);
    }

    public function test_seller_can_process_and_ship_order(): void
    {
        $customer = User::factory()->customer()->create();
        $product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'price' => 100000,
        ]);

        $order = Order::create([
            'order_number' => 'ORD-TEST-999',
            'user_id' => $customer->id,
            'status' => 'paid',
            'subtotal' => 100000,
            'discount_amount' => 0,
            'shipping_cost' => 10000,
            'grand_total' => 110000,
            'shipping_address' => [
                'recipient_name' => 'Budi',
                'phone' => '08123',
                'full_address' => 'Jl Merdeka',
            ],
            'shipping_courier' => 'SiCepat REG',
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'shop_id' => $this->shop->id,
            'product_id' => $product->id,
            'product_name' => $product->name,
            'price' => 100000,
            'quantity' => 1,
            'subtotal' => 100000,
        ]);

        Payment::create([
            'payment_number' => 'PAY-TEST-999',
            'order_id' => $order->id,
            'payment_method' => 'bank_transfer',
            'amount' => 110000,
            'status' => 'paid',
        ]);

        // 1. Process order (paid -> processing)
        $respProcess = $this->actingAs($this->seller)->post(route('seller.orders.process', $order->id));
        $respProcess->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);

        // 2. Ship order (processing -> shipped + tracking number)
        $respShip = $this->actingAs($this->seller)->post(route('seller.orders.ship', $order->id), [
            'tracking_number' => 'SCPT-99887766',
        ]);
        $respShip->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'shipped',
            'tracking_number' => 'SCPT-99887766',
        ]);
    }

    public function test_seller_can_update_shop_profile(): void
    {
        $response = $this->actingAs($this->seller)->put(route('seller.shop.update'), [
            'name' => 'Toko Maju Jaya Official',
            'description' => 'Toko resmi produk elektronik terbaik dengan garansi 1 tahun.',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'address' => 'Jl. Pemuda No. 45',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('shops', [
            'id' => $this->shop->id,
            'name' => 'Toko Maju Jaya Official',
            'city' => 'Surabaya',
            'address' => 'Jl. Pemuda No. 45',
        ]);
    }
}
