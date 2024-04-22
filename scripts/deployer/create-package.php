<?php

require_once('rabbitMQLib.inc');

$connectionConfig = [
    "BROKER_HOST" => "website",
    "BROKER_PORT" => 5672,
    "USER" => "bookQuest",
    "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
    "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
    "EXCHANGE_TYPE" => "topic",
    "AUTO_DELETE" => true,
    "EXCHANGE" => "createPackageExchange",
    "QUEUE" => "createPackageQueue"
];


// Fetch arguments
$environment = $argv[1];
$name = $argv[2];
$file_location = $argv[3];
$install_arguments = trim($argv[4]);

$request = [
    "type" => "create-package",
    "environment" => $environment,
    "name" => $name,
    "file_location" => $file_location,
    "install_arguments" => $install_arguments
];

// Sends the message and waits for a response
$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);
$response = $client->send_request($request);

// Returns response
http_response_code($response["returnCode"]);
echo json_encode($response);
exit(0);
