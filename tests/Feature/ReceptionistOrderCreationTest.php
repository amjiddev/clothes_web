<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CustomerMeasurement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReceptionistOrderCreationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'receptionist']);
        Role::firstOrCreate(['name' => 'customer']);
    }

    protected function uniqueEmail(string $prefix): string
    {
        return sprintf('%s_%s@example.com', $prefix, uniqid());
    }

    protected function createReceptionist(): User
    {
        $user = User::forceCreate([
            'name' => 'Receptionist User',
            'email' => $this->uniqueEmail('receptionist'),
            'contact_number' => '03001234567',
            'password' => bcrypt('password'),
        ]);

        $user->syncRoles('receptionist');

        return $user;
    }

    protected function createProduct(): Product
    {
        $category = Category::forceCreate([
            'name' => 'Men Fashion',
            'slug' => 'men-fashion',
            'is_active' => true,
        ]);

        return Product::forceCreate([
            'category_id' => $category->id,
            'name' => 'Classic Shirt',
            'slug' => 'classic-shirt',
            'price' => 1500.00,
            'discount_price' => 1500.00,
            'stock_quantity' => 20,
            'is_active' => true,
        ]);
    }

    public function test_ready_made_order_creates_order_items_and_reduces_stock(): void
    {
        $this->actingAs($this->createReceptionist());

        $customer = User::forceCreate([
            'name' => 'Existing Customer',
            'email' => $this->uniqueEmail('customer'),
            'contact_number' => '03007654321',
            'password' => bcrypt('password'),
        ]);
        $customer->syncRoles('customer');

        $product = $this->createProduct();

        $response = $this->post(route('receptionist.orders.store'), [
            'customer_id' => $customer->id,
            'order_type' => 'ready_made',
            'product_ids' => [$product->id],
            'quantities' => [2],
            'subtotal' => 3000.00,
            'stitching_charge' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 3000.00,
            'delivery_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'type' => 'ready_made']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 2]);
        $this->assertDatabaseMissing('stitching_orders', ['order_id' => Order::first()->id]);
        $this->assertSame(18, Product::find($product->id)->stock_quantity);
    }

    public function test_stitching_order_creates_stitching_record(): void
    {
        $this->actingAs($this->createReceptionist());

        $customer = User::forceCreate([
            'name' => 'Stitching Customer',
            'email' => $this->uniqueEmail('stitching'),
            'contact_number' => '03001112222',
            'password' => bcrypt('password'),
        ]);
        $customer->syncRoles('customer');

        $measurement = CustomerMeasurement::forceCreate([
            'user_id' => $customer->id,
            'profile_name' => 'Standard Shirt',
            'chest' => 40,
            'shoulder' => 18,
            'sleeve_length' => 24,
            'shirt_length' => 30,
            'neck' => 16,
            'waist' => 34,
            'trouser_length' => 40,
            'bottom' => 20,
            'thigh' => 25,
            'cuff_size' => 12,
        ]);

        $response = $this->post(route('receptionist.orders.store'), [
            'customer_id' => $customer->id,
            'order_type' => 'stitching',
            'subtotal' => 0,
            'stitching_charge' => 1500.00,
            'discount' => 0,
            'tax' => 0,
            'total' => 1500.00,
            'fabric_type' => 'Cotton',
            'fabric_color' => 'Navy',
            'garment_type' => 'shirt',
            'measurement_id' => $measurement->id,
            'stitching_instructions' => 'Need slim fit',
            'delivery_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'type' => 'stitching']);
        $this->assertDatabaseHas('stitching_orders', ['order_id' => Order::first()->id, 'garment_type' => 'shirt']);
        $this->assertDatabaseCount('order_items', 0);
    }

    public function test_combined_order_creates_product_and_stitching_records(): void
    {
        $this->actingAs($this->createReceptionist());

        $customer = User::forceCreate([
            'name' => 'Combined Customer',
            'email' => $this->uniqueEmail('combined'),
            'contact_number' => '03009998888',
            'password' => bcrypt('password'),
        ]);
        $customer->syncRoles('customer');

        $product = $this->createProduct();
        $measurement = CustomerMeasurement::forceCreate([
            'user_id' => $customer->id,
            'profile_name' => 'Custom Suit',
            'chest' => 42,
            'shoulder' => 19,
            'sleeve_length' => 25,
            'shirt_length' => 31,
            'neck' => 17,
            'waist' => 36,
            'trouser_length' => 42,
            'bottom' => 22,
            'thigh' => 26,
            'cuff_size' => 13,
        ]);

        $response = $this->post(route('receptionist.orders.store'), [
            'customer_id' => $customer->id,
            'order_type' => 'combined',
            'product_ids' => [$product->id],
            'quantities' => [1],
            'subtotal' => 1500.00,
            'stitching_charge' => 800.00,
            'discount' => 0,
            'tax' => 0,
            'total' => 2300.00,
            'fabric_type' => 'Silk',
            'fabric_color' => 'Maroon',
            'garment_type' => 'suit',
            'measurement_id' => $measurement->id,
            'stitching_instructions' => 'Need premium finish',
            'delivery_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', ['user_id' => $customer->id, 'type' => 'combined']);
        $this->assertDatabaseHas('order_items', ['product_id' => $product->id, 'quantity' => 1]);
        $this->assertDatabaseHas('stitching_orders', ['order_id' => Order::where('type', 'combined')->first()->id, 'garment_type' => 'suit']);
        $this->assertSame(19, Product::find($product->id)->stock_quantity);
    }

    public function test_new_customer_ready_made_order_creates_user_and_order(): void
    {
        $this->actingAs($this->createReceptionist());

        $product = $this->createProduct();

        $newCustomerEmail = $this->uniqueEmail('walkin');

        $response = $this->post(route('receptionist.orders.store'), [
            'new_customer_name' => 'Walk In Customer',
            'new_customer_email' => $newCustomerEmail,
            'new_customer_phone' => '03001234567',
            'new_customer_city' => 'Lahore',
            'new_customer_address' => 'Test Address',
            'order_type' => 'ready_made',
            'product_ids' => [$product->id],
            'quantities' => [1],
            'subtotal' => 1500.00,
            'stitching_charge' => 0,
            'discount' => 0,
            'tax' => 0,
            'total' => 1500.00,
            'delivery_date' => now()->addDay()->format('Y-m-d'),
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => $newCustomerEmail, 'contact_number' => '03001234567']);
        $this->assertDatabaseHas('orders', ['type' => 'ready_made']);
        $this->assertTrue(User::where('email', $newCustomerEmail)->exists());
    }
}
