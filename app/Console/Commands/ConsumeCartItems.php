<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//use Mateusjunges\Kafka\Facades\Kafka;
use Junges\Kafka\Facades\Kafka;
use Illuminate\Support\Facades\DB;

class ConsumeCartItems extends Command
{
    protected $signature = 'kafka:consume:cart_items';
    protected $description = 'Consume messages from "cart_items" topic and store items in the database.';

    public function handle()
    {
        $consumer = Kafka::createConsumer(['cart_items'],  config('kafka.consumer_group_id'), config('kafka.brokers'))->build();
        $consumer->consume( function ($message) {
            $data = json_decode($message->payload, true);

            // Store the cart item in the database for the user
            DB::table('db_carts')->insert([
                'user_id' => $data['user_id'],
                'product_id' => $data['product_id'],
            ]);
        });
    }
}