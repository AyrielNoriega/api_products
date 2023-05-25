<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use Laravel\Sanctum\Sanctum;
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
        Product::factory()->count(5)->create(); 

        $response = $this->getJson('/api/products');

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

        $data = [
            'name' => 'Nuevo producto',
            'price' => 1000,
        ];

       // $data = Product::factory(1)->create(); 
        
        $response = $this->postJson('/api/products', $data);

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        $this->assertDatabaseHas('products', $data);
    }

    public function test_show_product()
    {
        $product = Product::factory()->create();

        $response = $this->getJson("/api/products/{$product->getKey()}");

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
            'name' => 'Update Product',
            'price' => 20000,
        ];

        $response = $this->patchJson("/api/products/{$product->getKey()}", $data);
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

        $response = $this->deleteJson("/api/products/{$product->getKey()}");

        $response->assertSuccessful();
        $response->assertHeader('content-type', 'application/json');
        // $response->assertDeleted($product);
    }

}