#!/usr/bin/php
<?php
// Include required files
require_once ('rabbitMQLib.inc');
include_once ("functions/getAllUpdates.php");
include_once ("functions/addUpdate.php");

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
    if (!isset ($request['type'])) {
        return "ERROR: unsupported message type";
    }

    // Determine request type and call corresponding function
    if ($request['type'] === "add_update") {
        // Extract parameters from the request
        $user_email = $request["user_email"];
        $email_type = $request["email_type"];
        $query = $request["query"];

        // Call addUpdate function to add the update
        return addUpdate($user_email, $email_type, $query);
    } elseif ($request['type'] === "get_all_updates") {

        return getAllUpdates();
    }

    // Default return if request type is not recognized
    return array("returnCode" => '0', 'message' => "");
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
    "EXCHANGE" => "emailExchange",
    "QUEUE" => "emailQueue",
];

// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>