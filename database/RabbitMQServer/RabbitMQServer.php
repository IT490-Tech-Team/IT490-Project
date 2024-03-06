#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doRegister($username, $password)
{
  try {
    $conn = new mysqli('localhost', 'guest', '3394dzwHi0HJimrA13JO', 'userdb');
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error connecting to the database"
    );
    return $response;
  }

  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    echo "hi";
    $response = array(
      "returnCode" => '400',
      "message" => "Error registering user: " . $e->getMessage() // Append exception message to the error message
    );
    return $response;
  }

  if ($result === TRUE) {
    $response = array(
      "returnCode" => '200',
      "message" => "Registration successful"
    );
    return $response;
  } else {
    $response = array(
      "returnCode" => '400',
      "message" => "Error registering user"
    );
    return $response;
  }
}

function doLogin($username, $password)
{
  try {
    $conn = new mysqli('localhost', 'guest', '3394dzwHi0HJimrA13JO', 'userdb');
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error connecting to the database"
    );
    return $response;
  }

  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $response = array(
      "returnCode" => '200',
      "message" => "Login successful"
    );
    return $response;
  } else {
    $response = array(
      "returnCode" => '400',
      "message" => "Invalid username or password"
    );
    return $response;
  }

  return true;
}

function requestProcessor($request)
{
  echo "received request" . PHP_EOL;
  var_dump($request);
  if (!isset($request['type'])) {
    return "ERROR: unsupported message type";
  }

  if ($request['type'] === "login") {
    return doLogin($request['username'], $request['password']);
  } elseif ($request['type'] === "register") {
    return doRegister($request['username'], $request['password']);
  } elseif ($request['type'] === "validate_session") {
    return doValidate($request['sessionId']);
  }

  return array("returnCode" => '0', 'message' => "Server received request and processed");
}

$server = new rabbitMQServer("RabbitMQ.ini", "server");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>