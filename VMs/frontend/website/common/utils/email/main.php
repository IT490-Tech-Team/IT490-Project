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

// Check the type of request
if ($type === "add_update") {
    // Set request parameters for adding an update
    $request["user_id"] = isset($_POST["user_id"]) ? $_POST["user_id"] : null;
    $request["type"] = isset($_POST["type"]) ? $_POST["type"] : null;
    $request["query"] = isset($_POST["query"]) ? $_POST["query"] : null;
} elseif ($type === "get_all_updates") {
}
// Sets request type
$request['type'] = $type;


// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>