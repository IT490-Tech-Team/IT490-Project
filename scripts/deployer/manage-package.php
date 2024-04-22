#!/usr/bin/php
<?php

require_once('rabbitMQLib.inc');

$environment = $argv[1];
$action = $argv[2];
$packageName = $argv[3];
$packageVersion = $argv[4];

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

$request = [
    "type" => "manage-package",
    "packageName" => $packageName,
    "packageVersion" => $packageVersion,
    "action" => $action,
    "environment" => $environment,
];

// Sends the message and waits for a response
$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);
$response = $client->send_request($request);

// Returns response
http_response_code($response["returnCode"]);
echo json_encode($response);
exit(0);
