<?php

namespace App\Listeners;

use App\Models\DbCart;
use App\Events\CartItemEvent;
use Junges\Kafka\Facades\Kafka;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

class CartItemListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected $kafka;
    
    public function __construct(Kafka $kafka)
    {
        $this->kafka = $kafka;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CartItemEvent  $event
     * @return void
     */
    public function handle(CartItemEvent $event)
    {
        $topic = $event->topic;

         // Add or remover cart from DB
         $consumer = $this->kafka->createConsumer([$topic])
         ->withHandler(function (KafkaConsumerMessage $message) {
            $data = $message->getBody();
            if ($data['action'] === 'add') {
                // Store the item in the user's cart in the database
                DbCart::create([
                    'user_id' => $data['user_id'],
                    'product_id' => $data['product_id'],
                ]);
            } elseif ($data['action'] === 'remove') {
                // Remove the item from the user's cart in the database
                DbCart::where('user_id', $data['user_id'])
                    ->where('product_id', $data['product_id'])
                    ->delete();
            }
         })->build();

        $consumer->consume();
        
    }
}
