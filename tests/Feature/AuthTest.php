<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Admin']);
        Role::firstOrCreate(['slug' => 'seller'], ['name' => 'Seller']);
        Role::firstOrCreate(['slug' => 'customer'], ['name' => 'Customer']);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang!');
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'testuser@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('home'));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->customer()->create([
            'email' => 'testuser@example.com',
            'password' => 'secret123',
        ]);

        $response = $this->post(route('login'), [
            'email' => 'testuser@example.com',
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_users_can_register_as_customer(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'John Customer',
            'email' => 'john@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'register_as_seller' => 0,
        ]);

        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'name' => 'John Customer',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        $this->assertTrue($user->isCustomer());
        $this->assertNotNull($user->cart);
        $response->assertRedirect(route('home'));
    }

    public function test_users_can_register_as_seller_and_create_shop(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Jane Seller',
            'email' => 'jane@example.com',
            'phone' => '08987654321',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'register_as_seller' => 1,
            'shop_name' => 'Jane Official Tech',
            'shop_city' => 'Jakarta Barat',
        ]);

        $this->assertAuthenticated();
        $user = User::where('email', 'jane@example.com')->first();
        $this->assertTrue($user->isSeller());
        $this->assertNotNull($user->shop);
        $this->assertEquals('Jane Official Tech', $user->shop->name);
        $this->assertEquals('Jakarta Barat', $user->shop->city);
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->customer()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }
}
