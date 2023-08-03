<?php
namespace App\Kafka;

use Junges\Kafka\Kafka;
use App\Events\CartItemEvent;

use App\Events\CartCheckoutEvent;
use Junges\Kafka\Message\Message;


class MainKafka
{
    protected $broker;
    protected $groupID;
    protected $kafka;

    public function __construct(Kafka $kafka)
    {
        $this->broker = config('kafka.brokers');
        $this->groupID = config('kafka.consumer_group_id');
        $this->kafka = $kafka;
    }

    //producer method
    public function produce($topic = 'cart_items', array $message = [])
    {
        $producer = $this->kafka->publishOn($topic, config('kafka.brokers'))
        ->withMessage($this->setMessage($topic, $message))
        ->withDebugEnabled();
        $producer->send();
        if($topic == 'checkout'){
            event( new CartCheckoutEvent($topic));
        }
        else{
            event(new CartItemEvent($topic));
        }
    }

    //get the message from the controller and set message for kafka 
    public function setMessage($topic, array $message)
    { 
        $message = new Message(
            topicName: $topic,
            headers: ['header-key' => 'header-value'],
            body: $message,
            key: 'kafka key here'  
        );
        return $message;
    }

}