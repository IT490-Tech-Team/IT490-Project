#!/usr/bin/php
<?php
// Include required files
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include_once('functions/addBooks.php');
include_once('functions/addCovers.php');
include_once('functions/searchBooks.php');

function getDatabaseConnection()
{
    $host = 'localhost';
    $username = 'bookQuest';
    $password = '3394dzwHi0HJimrA13JO';
    $database = 'booksdb';

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
    if ($request['type'] === "add") {
        return addBooks($request);
    } elseif ($request['type'] === "add_covers") {
        // Extract books array from request and decodes from string to array
        $books = $request["books"];
        return addCoverImageUrls(json_decode($books, true));
    } elseif ($request['type'] === "search") {
        $title = $request["title"];
        $author = $request["author"];
        $genre = $request["genre"];
        $language = $request["language"];
        $year = $request["year"];
        return searchBooks($title, $author, $genre, $language, $year);;
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
