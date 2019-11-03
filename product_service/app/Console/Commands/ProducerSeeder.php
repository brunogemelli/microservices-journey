<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;

class ProducerSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:product-producer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $container;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
    }


    public function handle()
    {
        $topicConfig = $this->container->get('KafkaTopicConfig');
        $brokerCollection = $this->container->get('KafkaBrokerCollection');

        $productId = Uuid::uuid4();
        $product = Product::create(
            [
                'id' => $productId,
                'name' => 'Product '.$productId,
                'description' => 'Description of product '.$productId,
                'price' => 100,
                'qty_available' => 100,
                'qty_total' => 500
            ]
        );

        $producer = new KafkaProducer(
            $brokerCollection,
            'products',
            $topicConfig
        );

        $producer->produce($product->toJson());
    }
}
