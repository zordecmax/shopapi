<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CartTest extends TestCase
{
    /**
     * Test view cart none logged.
     *
     * @return void
     */
    public function test_view_cart_none_logged_id()
    {
        $response = $this->getJson('/api/cart');
        $response->assertStatus(401);
    }

    /**
     * Test view cart logged in.
     *
     * @return void
     */
    public function test_view_cart_logged_id()
    {
        $user = User::factory()->create();
        $token = $user->createToken('apptoken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        Sanctum::actingAs(
            $user,
            ['*']
        );
        $response = $this->get('/api/cart');

        $response->assertStatus(200);

    }

    /**
     * Test add new item to cart .
     *
     * @return void
     */
    public function test_add_new_item_to_cart_none_logged_id()
    {
        $response = $this->postJson('/api/cart', [
            'name' => 'Air pods',
            'qty' => '2',
            'price' => '299',
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test add new item to cart .
     *
     * @return void
     */
    public function test_add_new_item_to_cart_logged_id()
    {
        $user = User::factory()->create();
        $token = $user->createToken('apptoken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $response = $this->postJson('/api/cart', [
            'id' => 1,
            'name' => 'Air pods',
            'qty' => 2,
            'price' => '299',
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test to update product in cart logged in.
     *
     * @return void
     */
    public function test_update_item_from_cart_logged_id()
    {
        $user = User::factory()->create();
        $token = $user->createToken('apptoken')->plainTextToken;
        $user->remember_token = $token;
        $user->save();

        Sanctum::actingAs(
            $user,
            ['*']
        );

        $addToUserCart = $this->postJson('/api/cart', [
            'id' => 1,
            'name' => 'Air pods',
            'qty' => '2',
            'price' => '299',
        ]);

        $response = $this->putJson('/api/cart/1',[
            'qty' => 3
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test to update product in cart.
     *
     * @return void
     */
    public function test_update_item_from_cart_none_logged_id()
    {
        $response = $this->putJson('/api/cart/1',[
            'qty' => 3
        ]);
        $response->assertStatus(401);
    }

    /**
     * Test delete all cart.
     *
     * @return void
     */
    public function test_delete_item_from_cart_none_logged_id()
    {
        $response = $this->deleteJson('/api/cart/1');
        $response->assertStatus(401);
    }


}
