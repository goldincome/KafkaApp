<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Log;
use Junges\Kafka\Message\Message;

class TestHandler
{
    public function __invoke(Message $message)
    {
        Log::debug('Message received!', [
            'body' => $message->getBody(),
            'headers' => $message->getHeaders(),
            'partition' => $message->getPartition(),
            'key' => $message->getKey(),
            'topic' => $message->getTopicName()
        ]);
    }
}