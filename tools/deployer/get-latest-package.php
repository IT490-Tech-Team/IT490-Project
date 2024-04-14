#!/usr/bin/php
<!-- exchange-queue message based -->
<?php

require_once ('rabbitMQLib.inc');
include_once ("functions/downloadPackage.php");
include_once ("functions/installPackage.php");

// Function to process incoming requests
function requestProcessor($request)
{
  global $machineType;
  echo $machineType;
  // Debug: Display received request
  echo "received request" . PHP_EOL;
  var_dump($request);

  // Check if request type is set
  if (!isset ($request['type'])) {
    return array("returnCode" => '500', 'message' => "Request unsupported.");
  }

  if($request['type'] === "install-package"){
	  $downloadPackageResponse = downloadPackage($request["environment"], "ubuntu", "ubuntu", $request["file_location"]);

    if($downloadPackageResponse["returnCode"] !== "200"){
      http_response_code($response["returnCode"]);
      echo json_encode($response);
      exit(0);
    }
  
    $packagePath = $downloadPackageResponse["message"];
    $projectDirectory= implode("/", array_slice(explode("/",$_SERVER["PWD"]),0,-2));

    installPackage($machineType, $packagePath, $projectDirectory, $request["install_arguments"]);
  }

  // Default return if request type is not recognized
  return array("returnCode" => '0', 'message' => "Request not processed.");
}

// Load environment configuration from JSON file
$environment = $argv[1];
$machineType = $argv[2];

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
    "file_location" => "/home/ubuntu/IT490-Project/packages/backups/backup_2024-04-14_02-42-13.zip",
    "install_arguments" => "-a -b -c -d -e -f -g -h -i -j -k"
];

  requestProcessor($request);
  // Main execution starts here
  echo "testRabbitMQServer BEGIN" . PHP_EOL;
  // Process incoming requests
  $server->process_requests('requestProcessor');
  echo "testRabbitMQServer END" . PHP_EOL;