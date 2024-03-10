#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('./functions/register.php');
require_once('./functions/login.php');
require_once('./functions/validate.php');

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

  // Default Return
  return array("returnCode" => '0', 'message' => "Request not processed.");
}

$server = new rabbitMQServer("RabbitMQ.ini", "development");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>