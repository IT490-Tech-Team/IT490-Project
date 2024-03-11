#!/usr/bin/php
<?php
// Include required files
require_once('dependencies/path.inc');
require_once('dependencies/get_host_info.inc');
require_once('dependencies/rabbitMQLib.inc');
require_once('functions/register.php');
require_once('functions/login.php');
require_once('functions/validate.php');

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
    return doLogin($request['username'], $request['password']);
  } elseif ($request['type'] === "register") {
    return doRegister($request['username'], $request['password']);
  } elseif ($request['type'] === "validate_session") {
    return doValidate($request['sessionId']);
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
