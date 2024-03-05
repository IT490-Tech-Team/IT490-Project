<?php

require_once('../utils/RabbitMQClient/path.inc');
require_once('../utils/RabbitMQClient/get_host_info.inc');
require_once('../utils/RabbitMQClient/rabbitMQLib.inc');

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    http_response_code(400); // Bad Request
    echo json_encode(array("error" => "Request must be POST"));
    exit;
}

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

$client = new rabbitMQClient("RabbitMQ.ini","server");

$request = array();
$request['type'] = "login";
$request['username'] = $username;
$request['password'] = $password;

$response = $client->send_request($request);
echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";
echo json_encode($response);

exit(0);
?>