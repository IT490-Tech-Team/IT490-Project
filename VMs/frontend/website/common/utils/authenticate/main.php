<?php

require_once('../RabbitMQClient/path.inc');
require_once('../RabbitMQClient/get_host_info.inc');
require_once('../RabbitMQClient/rabbitMQLib.inc');

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
$request['type'] = $type;

if ($type === "login" || $type === "register") {
    if (!(isset($_POST["username"])) || !(isset($_POST["password"]))){
        $response = array(
            "returnCode" => '400',
            "message" => "Bad Request: Username or password not set"
        );
        echo json_encode($response);
        exit;
    }

    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $response = array(
            "returnCode" => '400',
            "message" => "Bad Request: All fields are required."
        );
        echo json_encode($response);
        exit;
    }

    $request['username'] = $username;
    $request['password'] = $password;
} elseif ($type === "validate_session") {
    if (!(isset($_POST["sessionId"]))){
        $response = array(
            "returnCode" => '400',
            "message" => "Bad Request: SessionId not set"
        );
        echo json_encode($response);
        exit;
    }

    $sessionId = $_POST['sessionId'];

    if (empty($sessionId)) {
        $response = array(
            "returnCode" => '400',
            "message" => "Bad Request: SessionId is required."
        );
        echo json_encode($response);
        exit;
    }

    $request['sessionId'] = $sessionId;
}

$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);


exit(0);

?>
