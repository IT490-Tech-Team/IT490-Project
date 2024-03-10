#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request)
{
  echo "received request" . PHP_EOL;
  var_dump($request);
  return array("returnCode" => '0', 'message' => "Server received request and processed");
}

$server = new rabbitMQServer("RabbitMQ.ini", "server");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>