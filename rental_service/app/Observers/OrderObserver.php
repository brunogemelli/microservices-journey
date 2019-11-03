<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    private $producer;

    public function __construct(ContainerInterface $container)
    {
        $this->producer = new KafkaProducer(
            $container->get('KafkaBrokerCollection'),
            'orders',
            $container->get('KafkaTopicConfig')
        );
    }

    public function created(Order $order)
    {
        $order->adjustTotal();
        $this->producer->produce($this->prepareOrder($order)->toJson());
    }

    public function updated(Order $order)
    {
        $order->adjustTotal();
        $order->adjustBalance();
        $this->producer->produce($this->prepareOrder($order)->toJson());
    }

    public function prepareOrder(Order $order)
    {
        $preparedOrder = [
            'order' => [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'status' => $order->status,
                'discount' => $order->discount,
                'total' => $order->total,
                'date' => $order->date,
            ]
        ];

        $preparedOrderItems = [];

        foreach ($order->items as $orderItem) {
            $items['id'] = $orderItem->id;
            $items['order_id'] = $orderItem->order_id;
            $items['qty'] = $orderItem->qty;
            $items['total'] = $orderItem->qty * $orderItem->product->price;
            $items['product']['id'] = $orderItem->product->id;
            $items['product']['name'] = $orderItem->product->name;
            $preparedOrderItems = $items;
        }

        $preparedOrder['order']['items'] = $preparedOrderItems;

        return $preparedOrder;
    }
}
