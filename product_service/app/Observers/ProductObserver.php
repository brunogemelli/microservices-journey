<?php

namespace App\Observers;

use App\Models\Product;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class ProductObserver
{
    private $producer;

    public function __construct(ContainerInterface $container)
    {
        $this->producer = new KafkaProducer(
            $container->get('KafkaBrokerCollection'),
            'products',
            $container->get('KafkaTopicConfig')
        );
    }

    public function created(Product $product)
    {
        $this->producer->produce($product->toJson());
    }

    public function updated(Product $product)
    {
        $this->producer->produce($product->toJson());
    }

    public function deleted(Product $product)
    {
        //@todo
    }
}
