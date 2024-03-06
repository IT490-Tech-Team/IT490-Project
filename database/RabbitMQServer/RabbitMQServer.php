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

  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  if ($result->num_rows <= 0) {
    $response = array(
      "returnCode" => '400',
      "message" => "Invalid username or password"
    );
    return $response;
  }

  // Fetch the user details
  $row = $result->fetch_assoc();
  $userId = $row['id'];

  $sql = "DELETE FROM sessions WHERE userId = '$userId'";
  try {
    $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  // Create a new session for the user
  $sessionId = uniqid();
  $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
  $sql = "INSERT INTO sessions (sessionId, userId, expired_at) VALUES ('$sessionId', '$userId', '$expiryDate')";
  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  if ($result === TRUE) {
    // Return the session ID to the user
    return array(
      "returnCode" => '200',
      "sessionId" => $sessionId,
      "expired_at" => $expiryDate,
      "message" => "Login successful"
    );
  } else {
    // Return an error message if the session ID couldn't be inserted
    return array(
      "returnCode" => '500',
      "message" => "Error creating session"
    );
  }
  return true;
}

function doValidate($sessionId)
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

  // Validate session ID
  $sql = "SELECT * FROM sessions WHERE sessionId = '$sessionId'";

  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  if ($result->num_rows <= 0) {
    $response = array(
      "returnCode" => '400',
      "message" => "Invalid session ID"
    );
    return $response;
  }

  // Check if the session is expired
  $row = $result->fetch_assoc();
  $expiryDate = strtotime($row['expired_at']);
  $currentDate = strtotime(date('Y-m-d H:i:s'));

  if ($expiryDate < $currentDate) {
    $response = array(
      "returnCode" => '400',
      "message" => "Session expired"
    );
    return $response;
  }

  // Session is valid
  return array(
    "returnCode" => '200',
    "message" => "Session validated successfully"
  );
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