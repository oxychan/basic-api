<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateProdukTest extends TestCase
{
    use RefreshDatabase;

    private const PRODUCT_URL = 'api/product/';
    private $user;
    private $product;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->product = $this->createProduct();
    }

    public function createUser()
    {
        return User::factory()->create();
    }

    public function createProduct()
    {
        return Product::factory()->create();
    }

    public function test_update_product_by_id()
    {
        Sanctum::actingAs($this->user);

        $this->putJson(UpdateProdukTest::PRODUCT_URL . $this->product->id, [
            'name' => 'Product after update',
            'slug' => 'product-after-update',
            'description' => 'Description after update',
            'price' => 100
        ])
            ->assertStatus(200);
    }
}
