<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private const PRODUCT_URL = 'api/product';
    private const PRODUCT_BY_ID_URL = 'api/product/';
    private $products;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->products = $this->createProducts();
    }

    public function createProducts()
    {
        return Product::factory()->count(2)->create();
    }

    public function test_get_all_products()
    {               
        $this->get(ProductTest::PRODUCT_URL)
            ->assertStatus(200)
            ->assertJsonFragment([
                "data" => [
                   [
                        "created_at" => $this->products[1]->created_at, 
                        "description" => $this->products[1]->description, 
                        "id" => $this->products[1]->id, 
                        "name" => $this->products[1]->name, 
                        "price" => number_format($this->products[1]->price, 2), 
                        "slug" => $this->products[1]->slug, 
                        "updated_at" => $this->products[1]->updated_at 
                   ], 
                   [
                        "created_at" => $this->products[0]->created_at,  
                        "description" => $this->products[0]->description, 
                        "id" => $this->products[0]->id, 
                        "name" => $this->products[0]->name, 
                        "price" => number_format($this->products[0]->price, 2), 
                        "slug" => $this->products[0]->slug, 
                        "updated_at" => $this->products[0]->updated_at 
                   ], 
                ], 
            ]);
    }

    public function test_get_one_data()
    {
        $this->get(ProductTest::PRODUCT_BY_ID_URL . $this->products[0]->id)
            ->assertStatus(200)
            ->assertJsonFragment([
                "data" => 
                   [
                        "created_at" => $this->products[0]->created_at, 
                        "description" => $this->products[0]->description, 
                        "id" => $this->products[0]->id, 
                        "name" => $this->products[0]->name, 
                        "price" => number_format($this->products[0]->price, 2), 
                        "slug" => $this->products[0]->slug, 
                        "updated_at" => $this->products[0]->updated_at 
                   ]
            ]);
    }
}
