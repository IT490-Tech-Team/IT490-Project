#!/usr/bin/php
<?php
// Include required files
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include_once('functions/register.php');
include_once('functions/login.php');
include_once('functions/validate.php');
include_once('functions/getUser.php');

function getDatabaseConnection()
{
    $host = 'localhost';
    $username = 'bookQuest';
    $password = '3394dzwHi0HJimrA13JO';
    $database = 'bookShelf';

    // Create connection
    $conn = new mysqli($host, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        // Log error or handle as needed
        return null;
    }

    return $conn;
}

// Function to process incoming requests
function requestProcessor($request)
{
  // Debug: Display received request
  echo "received request" . PHP_EOL;
  var_dump($request);

  // Check if request type is set
  if (!isset($request['type'])) {
    return "ERROR: unsupported message type";
  }

  // Determine request type and call corresponding function
  if ($request['type'] === "login") {
    $username = $request['username'];
    $password = $request['password'];

    return doLogin($username, $password);
  } elseif ($request['type'] === "register") {
    $username = $request['username'];
    $password = $request['password'];
    $email = $request['email'];
    $updates_enabled = json_decode($request['updates_enabled']);

    return doRegister($username, $password, $email, $updates_enabled);
  } elseif ($request['type'] === "validate_session") {
    $sessionId = $request['sessionId'];

    return doValidate($sessionId);
  } elseif ($request['type'] === "get_user") {
    $sessionId = $request['sessionId'];
    
    return getUser($sessionId);
  }

  // Default return if request type is not recognized
  return array("returnCode" => '0', 'message' => "Request not processed.");
}

// Create RabbitMQ server instance
$server = new rabbitMQServer("RabbitMQ.ini", "development");

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>