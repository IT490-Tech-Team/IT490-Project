#!/usr/bin/php
<?php

// Include required files
require_once('rabbitMQLib.inc');
require_once('functions/query.php');

// Get the system hostname
$hostname = gethostname();

// Split the hostname into parts using '-' as delimiter
$parts = explode('-', $hostname);

// Check if the first part of the hostname is 'dev', 'test', or 'prod'
if (in_array($parts[0], ['dev', 'test', 'prod'])) {
    // Replace the second part with 'backend'
    $BROKER_HOST = $parts[0] . '-backend';
} else {
    // Set default value if there's an error or if the variable is null
    $BROKER_HOST = '127.0.0.1';
}

function getDatabaseConnection()
{
    $host = 'localhost';
    $username = 'bookQuest';
    $password = '3394dzwHi0HJimrA13JO';
    $database = 'bookShelf';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        // Set PDO to throw exceptions on error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Log error or handle as needed
        return null;
    }
}

// Function to process incoming requests
function requestProcessor($request)
{
    // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check request type
    if ($request['type'] === "search") {
        return handleQuery($request["title"]);
    }
}

$connectionConfig = [
    "BROKER_HOST" => $BROKER_HOST,
    "BROKER_PORT" => 5672,
    "USER" => "bookQuest",
    "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
    "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
    "EXCHANGE_TYPE" => "topic",
    "AUTO_DELETE" => true,
    "EXCHANGE" => "searchDmzExchange",
    "QUEUE" => "searchDmzQueue",
];


// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>
