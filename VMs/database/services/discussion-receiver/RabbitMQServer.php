#!/usr/bin/php
<?php
// Include required files
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include_once("functions/getDiscussions.php");
include_once("functions/addDiscussion.php");
include_once("functions/getDiscussionById.php");

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
    if ($request['type'] === "add_comment") {
        // Extract parameters from the request
        $book_id = $request["book_id"];
        $user_id = $request["user_id"];
        $username = $request["username"];
        $comment = $request["comment"];
        
        // Call addDiscussion function to add the comment
        return addDiscussion($book_id, $user_id, $username, $comment);
    } elseif ($request['type'] === "reply_comment") {
        // Extract parameters from the request
        $book_id = $request["book_id"];
        $user_id = $request["user_id"];
        $username = $request["username"];
        $comment = $request["comment"];
        $reply_to_id = $request["reply_to_id"]; // Assuming this is provided in the request
        
        // Call addDiscussion function to add the reply comment
        return addDiscussion($book_id, $user_id, $username, $comment, $reply_to_id);
    } elseif ($request['type'] === "get_comments") {
        // Extract the ID from the request
        $id = $request["id"];

        // Call getDiscussions function to get all discussions
        return getAllDiscussions($id);
    } elseif ($request['type'] === "get_comment_by_id") {
        // Extract the ID from the request
        $id = $request["id"];
        
        // Call getDiscussionById function to retrieve a specific discussion by ID
        return getDiscussionById($id);
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