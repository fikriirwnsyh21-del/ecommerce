<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
    }

    public function test_authenticated_user_can_view_cart(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->get(route('cart.index'));

        $response->assertStatus(200);
        $response->assertSee('Keranjang Belanja');
    }

    public function test_user_can_add_product_to_cart(): void
    {
        $user = User::factory()->customer()->create();
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Powerbank 20000mAh Fast Charging',
            'stock' => 15,
        ]);

        $response = $this->actingAs($user)->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }
}
