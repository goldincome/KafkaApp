<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Services\CartProducer;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{

    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware('auth');   
    }

    public function index()
    {
        $cartItems = $this->cartService->getCartItems();
        $total = $this->cartService->getCartTotal();
        return view('cart', compact('cartItems','total'));
    }

    public function addToCart(AddToCartRequest $request)
    {
        // Validate the incoming request data
        
        $product = Product::find($request->product_id);
        // Add the item to the cart
        $cart = $this->cartService->addToCart($product, $request->qty);
        return redirect()->route('cart.index');
    }

    public function removeFromCart(Request $request)
    {
        // Validate the incoming request data
        
        $userId = auth()->id(); 
        $product = Product::find($request->product_id);
        // Remove the item from the cart
        $this->cartService->removeFromCart($product);

        return redirect()->route('cart.index'); 
    }


    public function checkout()
    {
        // Perform the checkout process
        // ...

        // Send message to Kafka topic "checkout"
        $productIds = $this->cartService->getProductIds();// Retrieve the list of product IDs in the cart
        $this->cartService->sendCheckoutMessage($productIds);

        return view('success');
    }

}
