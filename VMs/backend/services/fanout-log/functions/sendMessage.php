<?php

require_once __DIR__ . "/../rabbitMQLib.inc";

function sendMessage($message_type, $message, $source) {
    // RabbitMQ connection configuration
    $connectionConfig = [
        "BROKER_HOST" => "localhost",
        "BROKER_PORT" => 5672,
        "USER" => "bookQuest",
        "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
        "VHOST" => "bookQuest",
    ];

    // Define the exchanges and queues based on the stack
    $machines = ["frontend", "backend", "dmz"];

    // Create RabbitMQ client for each exchange and queue
    foreach ($machines as $service) {
        // Exchange and queue names
        $exchange = "$service-logExchange";
        $queue = "$service-logQueue";

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
        $sent_message = [
            "type" => "add_log",
            "message" => $message,
            "message_type" => $message_type,
            "source" => $source
        ];

        // Send the message
        $client->publish($sent_message);
    }
}

?>
