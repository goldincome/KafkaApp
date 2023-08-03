<?php

namespace App\Kafka;

use App\Kafka\MainKafka;

class KafkaProducer
{
    protected $kafka;

    public function __construct(MainKafka $kafka)
    {
        $this->kafka = $kafka;
    }

    public function sendToCartTopic(int $userId, int $productId, $action = 'add')
    {
        $topic = 'cart_items';

        $message = [
            'user_id' => $userId,
            'product_id' => $productId,
            'action' => $action,
        ];
       
        // producer sends the message to the Kafka topic
        $this->kafka->produce($topic, $message);
        //$message = new Message(['header-key' => 'header-value'], $message, '');
        //Kafka::publishOn(config('kafka.brokers'), 'cart_items')->withMessage($message)->send();
    }

    public function sendToCheckoutTopic(int $userId, array $productIds)
    {
        $topic = 'checkout';

        $message = [
            'user_id' => $userId,
            'product_ids' => $productIds,
        ];

        // producer sends message to the Kafka topic
        $this->kafka->produce($topic, $message);
    }
}
