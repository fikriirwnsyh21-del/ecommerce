<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use App\Services\CartService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartServiceTest extends TestCase
{
    use RefreshDatabase;

    protected CartService $cartService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->cartService = new CartService;

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
    }

    public function test_can_add_item_to_cart(): void
    {
        $user = User::factory()->customer()->create();
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'stock' => 10,
            'price' => 100000,
        ]);

        $item = $this->cartService->addItem($user, $product->id, null, 2);

        $this->assertEquals(2, $item->quantity);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_adding_item_exceeding_stock_throws_exception(): void
    {
        $user = User::factory()->customer()->create();
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'stock' => 5,
        ]);

        $this->expectException(Exception::class);
        $this->cartService->addItem($user, $product->id, null, 10);
    }

    public function test_can_update_quantity_and_remove_item(): void
    {
        $user = User::factory()->customer()->create();
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'stock' => 10,
        ]);

        $item = $this->cartService->addItem($user, $product->id, null, 2);
        $this->cartService->updateQuantity($user, $item->id, 4);

        $this->assertEquals(4, $item->fresh()->quantity);

        $this->cartService->removeItem($user, $item->id);
        $this->assertDatabaseMissing('cart_items', ['id' => $item->id]);
    }
}
