#!/usr/bin/php
<!-- exchange-queue message based -->
<?php

require_once ('rabbitMQLib.inc');
include_once ("functions/downloadPackage.php");
// Function to process incoming requests
function requestProcessor($request)
{
  // Debug: Display received request
  echo "received request" . PHP_EOL;
  var_dump($request);

  // Check if request type is set
  if (!isset ($request['type'])) {
    return "ERROR: unsupported message type";
  }

  if($request['type'] === "install-package"){
	downloadPackage($request["environment"], "ubuntu", "ubuntu", $request["file_location"]);
  }

  // Default return if request type is not recognized
  return array("returnCode" => '0', 'message' => "Request not processed.");
}

// Load environment configuration from JSON file
$environment = $argv[1];
$environmentConfig = json_decode(file_get_contents('environment.json'), true);

if (!isset($environmentConfig[$environment])) {
    echo "Environment '$environment' not found in configuration." . PHP_EOL;
    exit(1);
}

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
    "EXCHANGE" => $environmentConfig[$environment]['EXCHANGE'],
    "QUEUE" => $environmentConfig[$environment]['QUEUE'],
  ];
  
  // Create RabbitMQ server instance
  $server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);
  
  $request = [
    "type" => "install-package",
    "environment" => "deployer",
    "name" => "main",
    "file_location" => "/home/ubuntu/IT490-Project/packages/backups/backup_2024-04-13_17-18-12.zip",
    "install_arguments" => "-a -b -c -d -e -f -g -h -i -j -k"
];

  requestProcessor($request);
  // Main execution starts here
  echo "testRabbitMQServer BEGIN" . PHP_EOL;
  // Process incoming requests
  $server->process_requests('requestProcessor');
  echo "testRabbitMQServer END" . PHP_EOL;