<?php

namespace App\Listeners;

use Junges\Kafka\Facades\Kafka;
use App\Events\CartCheckoutEvent;
use App\Models\Order;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Junges\Kafka\Contracts\KafkaConsumerMessage;

class CartCheckoutListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */

    protected  $kafka;

    public function __construct(Kafka $kafka)
    {
     $this->kafka =  $kafka;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\CartCheckoutEvent  $event
     * @return void
     */
    public function handle(CartCheckoutEvent $event)
    {
        $topic = $event->topic;
        // Add Order to database
        $consumer = $this->kafka->createConsumer([$topic])
        ->withHandler(function (KafkaConsumerMessage $message) {
            $data = $message->getBody();
            // Store the item in the order table in the database
            Order::create([
                'user_id' => $data['user_id'],
                'product_ids' => json_encode($data['product_ids']),
            ]);
        })->build();

       $consumer->consume();
    } 
}
