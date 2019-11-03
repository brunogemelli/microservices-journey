<?php

namespace App\Kafka;

use PHPEasykafka\KafkaConsumerHandlerInterface;

class OrderHandler implements KafkaConsumerHandlerInterface
{

    public function __invoke(
        \RdKafka\Message $message,
        \RdKafka\KafkaConsumer $consumer
    )
    {
        echo $message->payload;
    }
}
