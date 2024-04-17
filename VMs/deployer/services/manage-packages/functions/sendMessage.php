<?php

require_once __DIR__ . "/../rabbitMQLib.inc";

function sendMessage($stack, $name, $file_location, $install_arguments) {
    // RabbitMQ connection configuration
    $connectionConfig = [
        "BROKER_HOST" => "localhost",
        "BROKER_PORT" => 5672,
        "USER" => "bookQuest",
        "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
        "VHOST" => "bookQuest",
    ];

    // Define the exchanges and queues based on the stack
    $exchangesQueues = [
        "dev" => ["frontend", "backend", "dmz"],
        "test" => ["frontend", "backend", "dmz"],
        "prod" => ["frontend", "backend", "dmz"]
    ];

    // Create RabbitMQ client for each exchange and queue
    foreach ($exchangesQueues[$stack] as $service) {
        // Exchange and queue names
        $exchange = "$stack-$service-Exchange";
        $queue = "$stack-$service-Queue";

        // Exchange and queue configuration
        $exchangeQueueConfig = [
            "EXCHANGE_TYPE" => "topic",
            "AUTO_DELETE" => true,
            "EXCHANGE" => $exchange,
            "QUEUE" => $queue
        ];

        // Create RabbitMQ client
        $client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);

        // Define the message payload
        $message = [
            "type" => "install-package",
            "environment" => "deployer",
            "name" => $name,
            "file_location" => $file_location,
            "install_arguments" => $install_arguments
        ];

        // Send the message
        $client->publish($message);
    }
}

?>
