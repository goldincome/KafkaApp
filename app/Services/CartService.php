<?php
namespace App\Services;

use App\Models\Product;
use Darryldecode\Cart\Cart;
use App\Kafka\KafkaProducer;
use Illuminate\Support\Collection;

class CartService extends Cart
{
    protected $kafkaProducer;
    protected $session;
    protected $event;
    protected $sessionId;
    protected $userId;

    public function __construct(KafkaProducer $kafkaProducer)
    {
        $this->kafkaProducer = $kafkaProducer;
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        $this->sessionId =  '5046k6Y7Q'. uniqid().time();
        $this->userId = auth()->id();
        parent::__construct($this->session, $this->event, 'kafka-cart','7T4K9Ussds',config('shopping_cart'));
    }

    public function getSession()
    {
        return app()->make('session');
    }

    public function getEvents()
    {
        return app()->make('events');
    }

    public function addToCart(Product $product, $qty = 1): Cart
    {
        // Add the item to the cart in the database
        $cart = $this->add( [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price, 
            'quantity' => $qty,
            ]);
        
        // Send message to Kafka topic "cart_items"
        $this->kafkaProducer->sendToCartTopic(auth()->id(), $product->id);
        return $cart;
    }

    public function removeFromCart(Product $product)
    {
        // Remove the item from the cart in the database
        try {
            $this->remove($product->id);
        } catch (\Throwable $e) {
            throw ('Product in cart not found.');
        }

        // Send message to Kafka topic "cart_items" for removing the item
        $this->kafkaProducer->sendToCartTopic(auth()->id(), $product->id, 'remove');
    }

    public function sendCheckoutMessage( array $productIds)
    {
        // ...

        // Send message to Kafka topic "checkout"
        $this->kafkaProducer->sendToCheckoutTopic(auth()->id(), $productIds);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCartItems() : Collection
    {
        return $this->getContent();
    }

    public function countItems() : int
    {
        return $this->getContent()->count();
    }


    /**
     * Get the final total of all the items in the cart minus tax
     *
     * @param int $decimals
     * @param float $shipping
     * @return float
     */
    public function getCartTotal()
    {
        if($this->getContent()->count() > 0){
            return number_format($this->getTotal(),2);
        }
        return 0;

    }
  
    public function isCartEmpty()
    {
        return $this->isEmpty();
    }

    
    /**
     * Return the specific item in the cart
     *
     * @param string $rowId
     * get CartItem
     */
    public function findItem(string $rowId): Collection
    {
        return $this->get($rowId);
    }

    /**
     * Clear the cart content
     */
    public function clearCart()
    {
        $this->clear();
    }

    // get all product ids in the cart
    public function getProductIds()
    {
        $ids = [];
        if($this->getContent()->count() > 0){
            foreach($this->getContent() as $row)
            {
                $ids[] = $row->id;
            
            }
        }
        return $ids;

    }

}
