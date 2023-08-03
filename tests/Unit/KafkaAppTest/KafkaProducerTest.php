<?php

use App\Kafka\KafkaProducer;
use App\Kafka\MainKafka;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KafkaProducerTest extends TestCase
{
    use RefreshDatabase; // This will refresh the database for each test

    public function testSendToCartTopic()
    {
        // Create a mock for MainKafka class
        $mainKafkaMock = $this->getMockBuilder(MainKafka::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Set the expectation that the produce method will be called with specific parameters
        $mainKafkaMock->expects($this->once())
            ->method('produce')
            ->with(
                $this->equalTo('cart_items'),
                $this->equalTo([
                    'user_id' => 1,
                    'product_id' => 123,
                    'action' => 'add',
                ])
            );

        // Create an instance of KafkaProducer with the mock MainKafka
        $kafkaProducer = new KafkaProducer($mainKafkaMock);

        // Call the method to be tested
        $kafkaProducer->sendToCartTopic(1, 123);
    }

    // Similarly, you can write test cases for other methods in KafkaProducer
}
