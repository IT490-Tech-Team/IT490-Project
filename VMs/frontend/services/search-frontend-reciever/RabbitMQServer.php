#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function saveBookCovers($bookCovers)
{
    $savedBookCovers = array();

    foreach ($bookCovers as $cover) {
        $bookId = $cover[0];
        $imageUrl = $cover[1];

        // Get the image data
        $imageData = file_get_contents($imageUrl);
        if ($imageData === false) {
            // Handle error if image retrieval fails
            // For example, you can log the error and continue with the next cover
            continue;
        }

        // Define the path to save the image
        $destinationPath = "/var/www/html/book_covers/{$bookId}.jpeg";

        // Save the image
        $bytesWritten = file_put_contents($destinationPath, $imageData);
        if ($bytesWritten === false) {
            // Handle error if image saving fails
            // For example, you can log the error and continue with the next cover
            continue;
        }

        // Update the array with the new URL
        $newImageUrl = "/book_covers/{$bookId}.jpeg";
        $savedBookCovers[] = array($bookId, $newImageUrl);
    }

    return $savedBookCovers;
}

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);

    if ($request['type'] === "download_covers"){
        $books = $request["books"];
        $database_books = saveBookCovers(json_decode($books, true));
        return array("returnCode" => '0', 'message' => $database_books);
    }
    
}

$server = new rabbitMQServer("RabbitMQ.ini", "development");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>