#!/usr/bin/php
<?php
// Include required files
require_once ('rabbitMQLib.inc');
include_once ("functions/getDiscussions.php");
include_once ("functions/addDiscussion.php");
include_once ("functions/getDiscussionById.php");

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
    if ($request['type'] === "add_comment") {
        // Extract parameters from the request
        $book_id = $request["book_id"];
        $user_id = $request["user_id"];
        $username = $request["username"];
        $comment = $request["comment"];

        if ($request["reply_to_id"] !== "null") {
            $reply_to_id = $request["reply_to_id"];
        }
        var_dump($reply_to_id);
        // Call addDiscussion function to add the reply comment
        return addDiscussion($book_id, $user_id, $username, $comment, $reply_to_id);
    } elseif ($request['type'] === "get_comment_by_book_id") {
        // Extract the ID from the request
        $id = $request["book_id"];

        // Call getDiscussions function to get all discussions
        return getDiscussionByBookId($id);
    } elseif ($request['type'] === "get_comment_by_comment_id") {
        // Extract the ID from the request
        $comment_id = $request["comment_id"];

        // Call getDiscussionById function to retrieve a specific discussion by ID
        return getDiscussionByDiscussionId($comment_id);
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
    "EXCHANGE" => "discussionExchange",
    "QUEUE" => "discussionQueue",
];

// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>