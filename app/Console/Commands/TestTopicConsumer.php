<?php

namespace App\Console\Commands;

use App\Handlers\TestHandler;
use Illuminate\Console\Command;
use Junges\Kafka\Facades\Kafka;

class TestTopicConsumer extends Command
{
    protected $signature = 'kafka:test-consume';

    protected $description = 'Command description';

    public function handle(): void
    {
        $consumer = Kafka::createConsumer(['test-topic'], config('kafka.brokers'), 'group-id')
            //->subscribe('test-topic')
            //->withHandler(new TestHandler)
            //->withAutoCommit()
            //->withConsumerGroupId('test-consumer')
            ->build();

        $consumer->consume();
    }
}