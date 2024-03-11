#!/usr/bin/php
<?php
// Include required files
require_once('dependencies/path.inc');
require_once('dependencies/get_host_info.inc');
require_once('dependencies/rabbitMQLib.inc');
require_once('functions/addBooks.php');
require_once('functions/addCovers.php');

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
    if ($request['type'] === "add") {
        $books = addBooks($request);
        return array("returnCode" => '200', 'message' => $books);
    } elseif ($request['type'] === "add_covers") {
        // Extract books array from request and decodes from string to array
        $books = $request["books"];
        $database_books = addCoverImageUrls(json_decode($books, true));
        return array("returnCode" => '200', 'message' => $database_books);
    }

    // Default return if request type is not recognized
    return array("returnCode" => '0', 'message' => "");
}

// Create RabbitMQ server instance
$server = new rabbitMQServer("RabbitMQ.ini", "development");

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>
