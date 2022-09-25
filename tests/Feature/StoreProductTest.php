<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreProductTest extends TestCase
{
    private const PRODUCT_URL = 'api/product';
    private const NAME = 'product for testing';
    private const SLUG = 'slug-for-testing';
    private const PRICE = 20;
    private const DESCRIPTION = 'product ini adalah untuk testing';
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
        Sanctum::actingAs($this->user);
    }

    public function createUser()
    {
        return User::factory()->create();
    }

    public function createProduct()
    {
        return Product::factory()->create([
            'name' => 'Testing product',
        ]);
    }

    public function test_store_produk()
    {          
        $this->postJson(StoreProductTest::PRODUCT_URL, $this->product->toArray())
            ->assertStatus(201);
        $this->assertDatabaseHas('products', $this->product->toArray());
    }

    public function test_all_field_is_required()
    {
        $payload = [
            'name' => '',
            'slug' => '',
            'description' => '',
            'price' => '',            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "description" => ["The description field is required." ], 
                "name" => ["The name field is required." ], 
                "price" => ["The price field is required."], 
                "slug" => ["The slug field is required."] 
             ]);
    }

    public function test_name_must_be_a_string()
    {
        $payload = [
            'name' => 24555,
            'slug' => StoreProductTest::SLUG,
            'description' => StoreProductTest::DESCRIPTION,
            'price' => StoreProductTest::PRICE,            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "name" => ["The name must be a string."], 
            ]);
    }

    public function test_name_at_least_five_characters()
    {
        $payload = [
            'name' => 'test', // only 4 chars
            'slug' => StoreProductTest::SLUG,
            'description' => StoreProductTest::DESCRIPTION,
            'price' => StoreProductTest::PRICE,            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "name" => ["The name must be at least 5 characters."], 
            ]);
    }

    public function test_slug_must_be_string()
    {
        $payload = [
            'name' => StoreProductTest::NAME,
            'slug' => 24,
            'description' => StoreProductTest::DESCRIPTION,
            'price' => StoreProductTest::PRICE,            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "slug" => ["The slug must be a string."], 
            ]);
    }

    public function test_description_must_be_a_string()
    {
        $payload = [
            'name' => StoreProductTest::NAME,
            'slug' => StoreProductTest::SLUG,
            'description' => 24,
            'price' => StoreProductTest::PRICE,            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "description" => ["The description must be a string."], 
            ]);
    }

    public function test_price_must_be_an_integer()
    {
        $payload = [
            'name' => StoreProductTest::NAME,
            'slug' => StoreProductTest::SLUG,
            'description' => StoreProductTest::DESCRIPTION,
            'price' => 'lima ribu',            
        ];

        $this->postJson(StoreProductTest::PRODUCT_URL, $payload)
            ->assertStatus(422)
            ->assertJsonFragment([
                "price" => ["The price must be an integer."], 
            ]);
    }
}
