#!/usr/bin/php
<?php
// Include required files
require_once('rabbitMQLib.inc');
include_once("functions/createLog.php");

// Get Path of JSON, Read JSON, Decode JSON

$BROKER_HOST = "127.0.0.1"; // Default
// * hostnames are: <Environment>-<MachineType> i.e. dev-frontend
// * dynamically changes the hostname depending on the server's hostname
// * i.e. dev-frontend turns into dev-backend
$hostname = explode("-", gethostname());
$rabbitMQQueueExchange = $hostname[1];
if($hostname[1] === "frontend" || $hostname[1] === "dmz"){
    $hostname[1] = "backend";
    $BROKER_HOST = implode("-",$hostname);
    $BROKER_HOST = implode("-",$hostname);
    $BROKER_HOST .= ".tortoise-daggertooth.ts.net";
}

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
    // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check if request type is set
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }

    // Determine request type and call corresponding function
    if ($request['type'] === "add_log") {
        // Extract parameters from the request
        $message_type = $request["message_type"];
        $message = $request["message"];
        $source = $request["source"];
        // date handled later
        
        // Call addReview function to add log
        return createLog($message_type, $message, $source);
    }

    // Default return if request type is not recognized
    return array("returnCode" => '0', 'message' => "");
}

$connectionConfig = [
    "BROKER_HOST" => $BROKER_HOST,
    "BROKER_PORT" => 5672,
    "USER" => "bookQuest",
    "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
    "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
    "EXCHANGE_TYPE" => "fanout",
    "AUTO_DELETE" => true,
    "EXCHANGE" => $rabbitMQQueueExchange . "-logExchange",
    "QUEUE" => $rabbitMQQueueExchange . "-logQueue",
];

// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>
