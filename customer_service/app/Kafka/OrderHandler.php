<?php

namespace App\Kafka;

use App\Models\Order;
use App\Models\Product;
use PHPEasykafka\KafkaConsumerHandlerInterface;

class OrderHandler implements KafkaConsumerHandlerInterface
{

    public function __invoke(
        \RdKafka\Message $message,
        \RdKafka\KafkaConsumer $consumer
    )
    {
        $payload = json_decode($message->payload);

        $order = Order::find($payload->order->id);

        if ($order) {
            $order->delete();
        }

        Order::create([
            'id' => $payload->order->id,
            'customer_id' => $payload->order->customer_id,
            'status' => $payload->order->status,
            'discount' => $payload->order->discount,
            'total' => $payload->order->total,
            'date' => $payload->order->date
        ]);

        foreach ($payload->order->items as $orderItem) {
            Product::firstOrCreate(
                ['id' => $orderItem->product->id],
                ['name' => $orderItem->product->name,]
            );

            OrderItem::create([
                'id' => $payload->order->id,
                'order_id' => $payload->order->order_id,
                'product_id' => $payload->order->product_id,
                'qty' => $payload->order->qty,
                'total' => $payload->order->total,
            ]);
        }

    }
}
