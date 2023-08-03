<?php
namespace Tests\Unit\KafkaAppTest;


use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Services\CartService;
use App\Http\Requests\AddToCartRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAddToCart()
    {
        // Create a product
        $product = Product::factory()->create();

        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a request object
        $request = AddToCartRequest::create(route('cart.add'), 'POST', [
            'product_id' => $product->id,
            'qty' => 1,
        ]);

        // Send the request to the controller
        $response = $this->app->make(\App\Http\Controllers\CartController::class)->addToCart($request);

        // Assert that the response is a redirect to cart.index route
        $response->assertRedirect(route('cart.index'));

        // Assert that the product is added to the cart
        $this->assertDatabaseHas('cart', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

   
    public function testRemoveFromCart()
    {
        $userId = 3;
        $productId = 467;

        $cartServiceMock = $this->mock(CartService::class);
        $cartServiceMock->shouldReceive('removeFromCart')->once()->with($userId, $productId);

        $response = $this->postJson('/remove-from-cart', ['user_id' => $userId, 'product_id' => $productId]);
        
        $response->assertJson(['message' => 'Item removed from cart successfully']);
        $response->assertStatus(200);
    }
}
