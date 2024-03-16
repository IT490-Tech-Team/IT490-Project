<?php

require_once('../RabbitMQClient/path.inc');
require_once('../RabbitMQClient/get_host_info.inc');
require_once('../RabbitMQClient/rabbitMQLib.inc');

// Checks if the request is a post request
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    $response = array(
        "returnCode" => '400',
        "message" => "Bad Request: Request must be POST"
    );
    echo json_encode($response);
    exit;
}

// Gets the message type for comparison
$type = $_POST["type"];

// Search Books type, these are all the filters one could apply.
if ($type === "add_comment" || $type === "reply_comment") {
    $request["book_id"] = isset($_POST["book_id"]) ? $_POST["book_id"] : null;
    $request["user_id"] = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
    $request["username"] = isset($_POST["username"]) ? $_POST["username"] : null;
    $request["comment"] = isset($_POST["comment"]) ? $_POST["comment"] : null;
    $request["reply_to_id"] = isset($_POST["reply_to_id"]) ? $_POST["reply_to_id"] : null; 
} elseif($type === "get_comment_by_id"){
    $request["id"] = isset($_POST["id"]) ? $_POST["id"] : null;
}
elseif ($type === "get_comments"){
    $request["id"] = isset($_POST["id"]) ? $_POST["id"] : null;
}

// Sets request type
$request['type'] = $type;


// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>