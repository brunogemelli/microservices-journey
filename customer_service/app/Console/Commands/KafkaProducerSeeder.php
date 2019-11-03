<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PHPEasykafka\KafkaProducer;
use Psr\Container\ContainerInterface;

class KafkaProducerSeeder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kafka:produce-order';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $topicConf = $this->container->get('KafkaTopicConfig');
        $brokerCollection = $this->container->get('KafkaBrokerCollection');

        $producer = new KafkaProducer(
            $brokerCollection,
            'orders',
            $topicConf
        );

        $producer->produce('Hello world');
    }
}
