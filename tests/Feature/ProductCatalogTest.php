<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    }

    public function test_homepage_can_be_rendered(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Kebutuhan Gaming Gen Z');
        $response->assertSee('max-w-[1720px]');
        $response->assertSee('overflow-x-hidden');
    }

    public function test_product_catalog_can_be_rendered(): void
    {
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $category->id,
            'name' => 'Super Gaming Headset Pro',
            'is_active' => true,
        ]);

        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('Super Gaming Headset Pro');
    }

    public function test_product_catalog_can_be_filtered_by_category(): void
    {
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);

        $catElectronic = Category::factory()->create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $catFashion = Category::factory()->create(['name' => 'Fashion', 'slug' => 'fashion']);

        $product1 = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $catElectronic->id,
            'name' => 'Kamera Mirrorless 4K',
        ]);

        $product2 = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $catFashion->id,
            'name' => 'Kemeja Katun Pria',
        ]);

        $response = $this->get(route('products.index', ['category' => 'elektronik']));

        $response->assertStatus(200);
        $response->assertSee('Kamera Mirrorless 4K');
        $response->assertDontSee('Kemeja Katun Pria');
    }

    public function test_product_catalog_can_be_searched(): void
    {
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Apple iPhone 15 Pro Max',
        ]);

        Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Sepatu Lari Nike Air',
        ]);

        $response = $this->get(route('products.index', ['q' => 'iPhone']));

        $response->assertStatus(200);
        $response->assertSee('Apple iPhone 15 Pro Max');
        $response->assertDontSee('Sepatu Lari Nike Air');
    }

    public function test_product_detail_page_can_be_rendered(): void
    {
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create(['user_id' => $seller->id]);
        $cat = Category::factory()->create();

        $product = Product::factory()->create([
            'shop_id' => $shop->id,
            'category_id' => $cat->id,
            'name' => 'Mechanical Keyboard RGB Wireless',
            'slug' => 'mechanical-keyboard-rgb-wireless',
            'description' => 'Deskripsi keyboard mekanik berkualitas tinggi.',
        ]);

        $response = $this->get(route('products.show', $product->slug));

        $response->assertStatus(200);
        $response->assertSee('Mechanical Keyboard RGB Wireless');
        $response->assertSee('Deskripsi keyboard mekanik berkualitas tinggi.');
    }

    public function test_shop_page_can_be_rendered(): void
    {
        $seller = User::factory()->seller()->create();
        $shop = Shop::factory()->create([
            'user_id' => $seller->id,
            'name' => 'MegaTech Official',
            'slug' => 'megatech-official',
        ]);

        $response = $this->get(route('shops.show', $shop->slug));

        $response->assertStatus(200);
        $response->assertSee('MegaTech Official');
    }

    public function test_homepage_renders_expanded_menus_and_services_bar(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee('Simulasi Rakit PC');
        $response->assertSee('Super Flash Sale');
        $response->assertSee('Pengiriman Instan 2 Jam');
        $response->assertSee('Garansi Resmi 100%');
        $response->assertSee('Gratis Rakit');
        $response->assertSee('Brand Partner Resmi:');
        $response->assertSee('ASUS ROG');
    }
}
