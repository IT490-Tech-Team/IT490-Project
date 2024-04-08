#!/usr/bin/php
<?php
// Include required files
require_once ('rabbitMQLib.inc');
include_once ("functions/createPackage.php");

function getDatabaseConnection()
{
  $host = 'localhost';
  $username = 'bookQuest';
  $password = '3394dzwHi0HJimrA13JO';
  $database = 'bookShelf';

  // Create connection
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    // Log error or handle as needed
    return null;
  }

  return $conn;
}

// Function to process incoming requests
function requestProcessor($request)
{

  $computers = array(
    "dev" => array(
        "hostname" => "dev-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    ),
    "test" => array(
        "hostname" => "test-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    ),
    "prod" => array(
        "hostname" => "prod-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    )
  );

  // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check if request type is set
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }

    // Check if request type is 'create-package'
    if ($request["type"] === "create-package") {
        // Check if 'environment' is set in the request
        if (!isset($request['environment'])) {
            return "ERROR: 'environment' not provided in request";
        }

        // Retrieve environment details from the $computers array
        $environment = $request['environment'];
        if (!array_key_exists($environment, $computers)) {
            return "ERROR: Invalid environment provided";
        }

        // Extract hostname, username, and password for the given environment
        $hostname = $computers[$environment]['hostname'];
        $username = $computers[$environment]['username'];
        $password = $computers[$environment]['password'];

        // Check if 'file_location' is set in the request
        if (!isset($request['file_location'])) {
            return "ERROR: 'file_location' not provided in request";
        }

        // Call createPackage function with retrieved details
        $result = createPackage($hostname, $username, $password, $request['file_location']);
        return $result;
    }

    // Default return if request type is not recognized
    return array("returnCode" => '0', 'message' => "Request not processed.");
}

$connectionConfig = [
  "BROKER_HOST" => "localhost",
  "BROKER_PORT" => 5672,
  "USER" => "bookQuest",
  "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
  "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
  "EXCHANGE_TYPE" => "topic",
  "AUTO_DELETE" => true,
  "EXCHANGE" => "createPackageExchange",
  "QUEUE" => "createPackageQueue",
];

// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>