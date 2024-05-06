<?php

require_once __DIR__ . "/../rabbitMQLib.inc";

function createLog($message_type, $message, $source) {
	try {
		// RabbitMQ connection configuration
		$connectionConfig = [
		    "BROKER_HOST" => "localhost",
		    "BROKER_PORT" => 5672,
		    "USER" => "bookQuest",
		    "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
		    "VHOST" => "bookQuest",
		];

		// Exchange and queue configuration
		$exchangeQueueConfig = [
		    "EXCHANGE_TYPE" => "topic",
		    "AUTO_DELETE" => true,
		    "EXCHANGE" => "logExchange",
		    "QUEUE" => "logQueue"
		];

		// Create RabbitMQ client
		$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);

		// Define the message payload
		$message = [
		    "type" => "add_log",
		    "message" => $message,
		    "message_type" => $message_type,
		    "source" => $source
		];

		// Send the message
		$client->publish($message);
	}
	catch (Exception $e) {
		var_dump("Log message error");
	}
}

?>
