<?php

namespace App\Observers;

use App\Models\Customer;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class CustomerObserver
{
    private $producer;

    public function __construct(ContainerInterface $container)
    {
        $this->producer = new KafkaProducer(
            $container->get('KafkaBrokerCollection'),
            'customers',
            $container->get('KafkaTopicConfig')
        );
    }

    /**
     * Handle the app models customer "created" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function created(Customer $customer)
    {
        $this->producer->produce($customer->toJson());
    }

    /**
     * Handle the app models customer "updated" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function updated(Customer $customer)
    {
        $this->producer->produce($customer->toJson());
    }

    /**
     * Handle the app models customer "deleted" event.
     *
     * @param Customer $customer
     * @return void
     */
    public function deleted(Customer $customer)
    {
        //
    }
}
