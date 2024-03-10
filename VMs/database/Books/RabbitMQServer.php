#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);

    if (!isset($request['type'])) {
      return "ERROR: unsupported message type";
    }

    if ($request['type'] === "dmz_add") {

    }
    return array("returnCode" => '0', 'message' => "");
}

$server = new rabbitMQServer("RabbitMQ.ini", "server");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>