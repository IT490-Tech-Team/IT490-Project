#!/usr/bin/php
<?php
// Include required files
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include_once("functions/getReviewByBookId.php");
include_once("functions/addReview.php");

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
    if ($request['type'] === "add_review") {
        // Extract parameters from the request
        $book_id = $request["book_id"];
        $user_id = $request["user_id"];
        $username = $request["username"];
        $rating= $request["rating"];
        $comment = $request["comment"];
        
        // Call addReview function to add the comment
        return addReview($book_id, $user_id, $username, $rating, $comment);
    } 
     elseif ($request['type'] === "get_reviews_by_bookid") {
        // Extract the ID from the request
        $book_id = $request["book_id"];
      
        // Call getReviewById function to retrieve a specific discussion by ID
        return getReviewById($id);
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