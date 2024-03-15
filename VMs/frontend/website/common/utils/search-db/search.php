<?php

require_once('../RabbitMQClient/path.inc');
require_once('../RabbitMQClient/get_host_info.inc');
require_once('../RabbitMQClient/rabbitMQLib.inc');

// Checks if the request is a post request
if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    $response = array(
        "returnCode" => '400',
        "message" => "Bad Request: Request must be POST"
    );
    echo json_encode($response);
    exit;
}

// Gets the message type for comparison
$type = $_POST["type"];

// Search Books type, these are all the filters one could apply.
if ($type === "search") {
    $request["title"] = isset($_POST["title"]) ? $_POST["title"] : null;
    $request["author"] = isset($_POST["author"]) ? $_POST["author"] : null;
    $request["genre"] = isset($_POST["genre"]) ? $_POST["genre"] : null;
    $request["language"] = isset($_POST["language"]) ? $_POST["language"] : null;
    $request["year"] = isset($_POST["year"]) ? $_POST["year"] : null;
}
// Add books
elseif ($type === "add") {
    $request['books'] = $_POST["books"];
}
elseif ($type === "add_to_library"){
    $request["user_id"] = $_POST["user_id"];
    $request["book_id"] = $_POST["book_id"];
}
// Sets request type
$request['type'] = $type;

// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>