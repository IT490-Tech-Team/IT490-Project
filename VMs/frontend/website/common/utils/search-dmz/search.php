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

$type = $_POST["type"];

elseif ($type === "search") {
    $request['title'] = $_POST["title"];
}

// Sets request type
$request['type'] = $type;

// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>