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

$type = $_POST['type'];
$request = array();

if($type === "login"){
    $request['username'] = isset($_POST["username"]) ? $_POST["username"] : null;
    $request['password'] = isset($_POST["password"]) ? $_POST["password"] : null;
}
if($type === "register"){
    $request['username'] = isset($_POST["username"]) ? $_POST["username"] : null;
    $request['password'] = isset($_POST["password"]) ? $_POST["password"] : null;
    $request['email'] = isset($_POST["email"]) ? $_POST["email"] : null; // Added line to capture email
    $request['updates_enabled'] = isset($_POST["updates_enabled"]) ? $_POST["updates_enabled"] : null; // Added line to capture updates_enabled
}
if($type === "validate_session"){
    $request['sessionId'] = isset($_POST["sessionId"]) ? $_POST["sessionId"] : null;
}
if($type === "get_user"){
    $request['sessionId'] = isset($_POST["sessionId"]) ? $_POST["sessionId"] : null;
}

// Sets request type
$request['type'] = $type;

// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);

?>
