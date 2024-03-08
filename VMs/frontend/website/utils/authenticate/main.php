<?php

require_once('../RabbitMQClient/path.inc');
require_once('../RabbitMQClient/get_host_info.inc');
require_once('../RabbitMQClient/rabbitMQLib.inc');

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Request must be POST"));
    exit;
}

$type = $_POST['type'];
$request = array();
$request['type'] = $type;

if ($type === "login" || $type === "register") {
    if (!(isset($_POST["username"])) || !(isset($_POST["password"]))){
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "Username or password not set"));
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "All fields are required."));
        exit;
    }

    $request['username'] = $username;
    $request['password'] = $password;
} elseif ($type === "validate_session") {
    if (!(isset($_POST["sessionId"]))){
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "SessionId not set"));
        exit;
    }

    $sessionId = $_POST['sessionId'];

    if (empty($sessionId)) {
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "SessionId is required."));
        exit;
    }

    $request['sessionId'] = $sessionId;
}

$client = new rabbitMQClient("RabbitMQ.ini","server");
$response = $client->send_request($request);

echo json_encode($response);

exit(0);

?>
