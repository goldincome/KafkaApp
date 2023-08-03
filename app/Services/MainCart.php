<?php
namespace App\Services;


use App\Models\Product;
use Darryldecode\Cart\Cart;
use Illuminate\Support\Collection;


class MainCart extends Cart
{
    //public static $defaultCurrency;

    protected $session;

    protected $event;

    protected $sessionId;

    public function __construct()
    {
        $this->session = $this->getSession();
        $this->event = $this->getEvents();
        $this->sessionId =  '5046k6Y7Q'. uniqid().time();
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

    public function addToCart(Product $product, $quantity = 1) : Cart
    {
        return $this->add( [
                                    'id' => $product->id,
                                    'name' => $product->name,
                                    'price' => $product->current_price, //$product->todayPrice,
                                    'quantity' => $quantity,
                                    ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getCartItems() : Collection
    {
        return $this->getContent();
    }


    public function removeToCart(string $rowId)
    {
        try {
            $this->remove($rowId);
        } catch (\Throwable $e) {
            throw ('Product in cart not found.');
        }
    }

   
    public function countItems() : int
    {
        return $this->getContent()->count();
    }

    /**
     * Get the sub total of all the items in the cart
     *
     * @param int $decimals
     * @return float
     */
    public function getCartSubTotal()
    {
        // dd($this->subtotal($decimals, '.', ''));
        return number_format($this->getSubTotal(true),2);
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
            // foreach($this->getContent() as $row)
            // {
            //     $total = $this->getTotal() + $this->showShippingFee() ; //+ $row->attributes->parking_fee;
            //     return number_format($total,2);
            // }
            return number_format($this->getTotal(),2);
        }
        return 0;

    }

  
    public function isCartEmpty()
    {
        return $this->isEmpty();
    }

    /**
     * @param string $rowId
     * @param array update values
     * @return CartItem
     */

    public function updateAll(string $rowId, array $ops) : bool
    {
        return $this->update($rowId, $ops);
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

}

