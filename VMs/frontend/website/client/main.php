<?php

require_once('rabbitMQLib.inc');

// Checks if the request is a post request
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    $response = array(
        "returnCode" => '400',
        "message" => "Bad Request: Request must be POST"
    );
    echo json_encode($response);
    exit;
}

// Sets POST data to request for rabbitMQ Message
$request = $_POST;

$BROKER_HOST = "127.0.0.1"; // Default

// * hostnames are: <Environment>-<MachineType> i.e. dev-frontend
// * dynamically changes the hostname depending on the server's hostname
// * i.e. dev-frontend turns into dev-backend 
$hostname = explode("-", gethostname());
$hostname[1] = "backend";
$BROKER_HOST = implode("-",$hostname);

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
    "EXCHANGE" => $request["exchange"],
    "QUEUE" => $request["queue"]
];

unset($request["exchange"]);
unset($request["queue"]);

// Sends the message and waits for a response
$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);
$response = $client->send_request($request);

// Returns response
header("Content-Type: application/json");
http_response_code($response["returnCode"]);
echo json_encode($response);
exit(0);

?>

