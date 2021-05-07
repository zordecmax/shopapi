<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /**
     * Test single product show existing id .
     *
     *
     * @return void
     */
    public function test_show_single_product()
    {
        Product::factory()->create();
        $response = $this->getJson('/api/products/1');

        $response
            ->assertStatus(200);
    }

    /**
     * Test single product show non existing id .
     *
     * @return void
     */
    public function test_show_single_product_none_exist()
    {
        $response = $this->getJson('/api/products/100');

        $response
            ->assertStatus(404);
    }

    /**
     * Test single product show non existing id .
     *
     * @return void
     */
    public function test_show_single_product_if_no_id_given()
    {
        $response = $this->getJson('/api/products/noid');

        $response
            ->assertStatus(404);
    }

    /**
     * Test list of products.
     *
     * @return void
     */
    public function test_show_list_of_products()
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
    }

    /**
     * Test search product.
     *
     * @return void
     */
    public function test_search_products()
    {
        $response = $this->getJson('/api/products/search/pho');
        $response->assertStatus(200);
    }

    /**
     * Test add product.
     *
     * @return void
     */
    public function test_add_one_product_none_logged_id()
    {
        $response = $this->postJson('/api/products', [
            'name' => 'Air pods',
            'slug' => 'air_pods',
            'price' => '299',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test update product.
     *
     * @return void
     */
    public function test_update_one_product_none_logged_id()
    {
        $response = $this->putJson('/api/products/2', [
            'name' => 'Air pods',
            'slug' => 'air_pods',
            'price' => '299',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test delete product.
     *
     * @return void
     */
    public function test_delete_one_product_none_logged_id()
    {
        $response = $this->deleteJson('/api/products/2');
        $response->assertStatus(401);
    }
}
