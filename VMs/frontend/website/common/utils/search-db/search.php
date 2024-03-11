<?php

require_once('../RabbitMQClient/path.inc');
require_once('../RabbitMQClient/get_host_info.inc');
require_once('../RabbitMQClient/rabbitMQLib.inc');

if ($_SERVER["REQUEST_METHOD"] !== "POST"){
    $response = array(
        "returnCode" => '400',
        "message" => "Bad Request: Request must be POST"
    );
    echo json_encode($response);
    exit;
}
$type = $_POST["type"];

if ($type === "search") {
    $title = $_POST["title"];
    $author = $_POST["author"];
    $genre = $_POST["genre"];
    $language = $_POST["language"];
    $year = $_POST["year"];
}
elseif ($type === "add") {
    $request['books'] = $_POST["books"];
}
elseif ($type === "add_covers") {
    $request['books'] = $_POST["books"];
}

$request['type'] = $type;

$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

echo json_encode($response);
exit(0);
?>