#!/usr/bin/php
<?php

require_once('rabbitMQLib.inc');
include_once ("functions/downloadPackage.php");
include_once ("functions/installPackage.php");

$connectionConfig = [
    "BROKER_HOST" => "deployer",
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
$packageName = $argv[1];
$machineType = $argv[2];
$version = isset($argv[3]) ? $argv[3] : null; // Get version if provided, otherwise null

$request = [
    "type" => "package-info",
    "name" => $packageName,
    "version" => $version // Add version to the request
];

// Sends the message and waits for a response
$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);
$response = $client->send_request($request);

$downloadPackageResponse = downloadPackage("deployer", "ubuntu", "ubuntu", $response["file_location"]);

if($downloadPackageResponse["returnCode"] !== "200"){
    http_response_code($response["returnCode"]);
    echo json_encode($response);
    exit(0);
}

$packagePath = $downloadPackageResponse["message"];
$projectDirectory= implode("/", array_slice(explode("/",$_SERVER["PWD"]),0,-2));

installPackage($machineType, $packagePath, $projectDirectory, $response["installation_flags"]);

// Returns response
http_response_code($response["returnCode"]);
echo json_encode($response);
exit(0);

?>
