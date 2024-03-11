#!/usr/bin/php
<?php

// Include required files
require_once('dependencies/path.inc');
require_once('dependencies/get_host_info.inc');
require_once('dependencies/rabbitMQLib.inc');
require_once('functions/saveCover.php');

// Function to process incoming requests
function requestProcessor($request)
{
    // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check request type
    if ($request['type'] === "download_covers") {
        // Save book covers
        $books = $request["books"];
        $databaseBooks = saveBookCovers(json_decode($books, true));
        // Return response
        return ["returnCode" => 0, 'message' => $databaseBooks];
    }
}

// Create RabbitMQ server instance
$server = new rabbitMQServer("RabbitMQ.ini", "development");

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>
