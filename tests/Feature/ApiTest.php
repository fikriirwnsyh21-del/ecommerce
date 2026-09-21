<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $customer;

    protected User $seller;

    protected Shop $shop;

    protected Category $category;

    protected Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);

        $this->customer = User::factory()->customer()->create([
            'email' => 'api.customer@example.com',
            'password' => bcrypt('password123'),
        ]);

        $this->seller = User::factory()->seller()->create();
        $this->shop = Shop::factory()->create(['user_id' => $this->seller->id]);
        $this->category = Category::factory()->create(['name' => 'Gadget']);

        $this->product = Product::factory()->create([
            'shop_id' => $this->shop->id,
            'category_id' => $this->category->id,
            'name' => 'Smartphone Android 5G',
            'price' => 3000000,
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    public function test_user_can_register_via_api(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'name' => 'Rina Wijaya',
            'email' => 'rina@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '081299887766',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Pendaftaran akun berhasil.',
            ])
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'user' => ['id', 'name', 'email'],
                ],
            ]);

        $this->assertDatabaseHas('users', ['email' => 'rina@example.com']);
    }

    public function test_user_can_login_and_receive_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'api.customer@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonStructure(['data' => ['token', 'user']]);
    }

    public function test_user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'api.customer@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJson(['success' => false]);
    }

    public function test_authenticated_user_can_get_profile(): void
    {
        $token = $this->customer->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'email' => 'api.customer@example.com',
                ],
            ]);
    }

    public function test_public_can_browse_products_and_categories(): void
    {
        $respProd = $this->getJson('/api/v1/products');
        $respProd->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonFragment(['name' => 'Smartphone Android 5G']);

        $respCat = $this->getJson('/api/v1/categories');
        $respCat->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonFragment(['name' => 'Gadget']);
    }

    public function test_public_can_view_single_product(): void
    {
        $response = $this->getJson("/api/v1/products/{$this->product->slug}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Smartphone Android 5G',
                    'price' => 3000000,
                ],
            ]);
    }

    public function test_customer_can_manage_cart_via_api(): void
    {
        $token = $this->customer->createToken('test-token')->plainTextToken;

        // Add to cart
        $respAdd = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 2,
            ]);

        $respAdd->assertStatus(201)
            ->assertJson(['success' => true]);

        // View cart
        $respView = $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/cart');

        $respView->assertStatus(200)
            ->assertJson(['success' => true])
            ->assertJsonFragment(['product_name' => 'Smartphone Android 5G']);
    }

    public function test_customer_can_checkout_and_pay_via_api(): void
    {
        $token = $this->customer->createToken('test-token')->plainTextToken;

        $address = Address::factory()->create([
            'user_id' => $this->customer->id,
            'is_primary' => true,
        ]);

        // Add to cart
        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/cart/add', [
                'product_id' => $this->product->id,
                'quantity' => 1,
            ]);

        // Checkout
        $respCheckout = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/checkout', [
                'address_id' => $address->id,
                'shipping_courier' => 'JNE Reguler',
                'payment_method' => 'bank_transfer',
            ]);

        $respCheckout->assertStatus(201)
            ->assertJson(['success' => true]);

        $orderId = $respCheckout->json('data.id');

        // Pay Order
        $respPay = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson("/api/v1/orders/{$orderId}/pay");

        $respPay->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'status' => 'paid',
                ],
            ]);
    }
}
