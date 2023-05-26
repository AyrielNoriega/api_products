<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;

use App\Models\Product;
use App\Models\User;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_index()
    {
        User::factory()->count(5)->create();
        Product::factory()->count(5)->create(); 

        $response = $this->getJson('/api/v1/products');

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonCount(5, 'data');
    }

    public function test_create_new_product()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $user = User::factory()->create();

        $data = [
            'title' => 'Nuevo producto',
            'description' => 'It is a long established fact that a reader will be distracted.',
            'price' => 1000,
            'user_id' => $user->id,
        ];

       // $data = Product::factory(1)->create(); 
        
        $response = $this->postJson('/api/v1/products', $data);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_show_product()
    {
        User::factory()->count(5)->create();
        $product = Product::factory()->create();

        $response = $this->getJson("/api/v1/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_update_product()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $product = Product::factory()->create();
        
        $data = [
            'title' => 'Nuevo producto',
            'description' => 'It is a long established fact that a reader will be distracted.',
            'price' => 20000,
        ];

        $response = $this->patchJson("/api/v1/products/{$product->getKey()}", $data);
        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
    }

    public function test_delete_product()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );

        $product = Product::factory()->create();

        $response = $this->deleteJson("/api/v1/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        // $response->assertDeleted($product);
    }

}