<?php

require_once __DIR__ . "/../rabbitMQLib.inc";

function sendMessage($exchange, $queue, $name, $file_location, $install_arguments) {
    // RabbitMQ connection configuration
    $connectionConfig = [
        "BROKER_HOST" => "localhost",
        "BROKER_PORT" => 5672,
        "USER" => "bookQuest",
        "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
        "VHOST" => "bookQuest",
    ];

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
    $test = $client->publish($message);
	echo $test;
}

?>
