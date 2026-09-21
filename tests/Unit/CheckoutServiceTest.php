<?php

namespace Tests\Unit;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\VoucherService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CheckoutService $checkoutService;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService;
        $this->checkoutService = new CheckoutService(new VoucherService);

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
    }

    public function test_checkout_processes_successfully_and_decrements_stock(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'price' => 100000,
            'stock' => 10,
            'sales_count' => 0,
        ]);

        $this->cartService->addItem($user, $product->id, null, 2);

        $order = $this->checkoutService->processCheckout($user, [
            'address_id' => $address->id,
            'shipping_courier' => 'Reguler (J&T Express - 2-3 Hari)',
            'payment_method' => 'Bank Transfer',
        ]);

        $this->assertNotNull($order);
        $this->assertEquals(200000, $order->subtotal);
        $this->assertEquals(15000, $order->shipping_cost);
        $this->assertEquals(215000, $order->grand_total);

        // Product stock decremented
        $this->assertEquals(8, $product->fresh()->stock);
        $this->assertEquals(2, $product->fresh()->sales_count);

        // Cart emptied
        $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);

        // Payment created
        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'status' => 'pending',
        ]);
    }

    public function test_checkout_fails_and_rolls_back_if_stock_insufficient(): void
    {
        $user = User::factory()->customer()->create();
        $address = Address::factory()->create(['user_id' => $user->id]);

        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'stock' => 2,
        ]);

        $this->cartService->addItem($user, $product->id, null, 2);

        // Simulate stock changed concurrently to 1
        $product->update(['stock' => 1]);

        $this->expectException(Exception::class);
        $this->checkoutService->processCheckout($user, [
            'address_id' => $address->id,
            'shipping_courier' => 'Reguler (J&T Express - 2-3 Hari)',
            'payment_method' => 'Bank Transfer',
        ]);
    }
}
