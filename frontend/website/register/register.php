<?php

require_once('../utils/RabbitMQClient/path.inc');
require_once('../utils/RabbitMQClient/get_host_info.inc');
require_once('../utils/RabbitMQClient/rabbitMQLib.inc');

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    echo json_encode("Request must be POST");
    exit (1);
}

if (!(isset($_POST["username"])) || !(isset($_POST["password"]))){
    echo json_encode("Error, username or password not set");
    exit (1);
}

$username = $_POST['username'];
$password = $_POST['password'];
$msg = "test message";
if (empty($username) || empty($password)) {
    echo json_encode("All fields are required.");
   exit(1);
}

$client = new rabbitMQClient("RabbitMQ.ini","server");

$request = array();
$request['type'] = "register";
$request['username'] = $username;
$request['password'] = $password;
$response = $client->send_request($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

echo $argv[0]." END".PHP_EOL;

?>