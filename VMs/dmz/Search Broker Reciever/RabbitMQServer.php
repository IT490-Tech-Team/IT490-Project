#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function handleQuery($request){
    // Construct the URL
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($request["title"]);

    // Initialize curl session
    $curl = curl_init();

    // Set the curl options
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true, // Follow redirects
        CURLOPT_HEADER => false, // Exclude header from output
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (use with caution)
        CURLOPT_USERAGENT => 'Your User Agent String Here', // Set a user agent string
    ));

    // Execute the curl request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        $error = curl_error($curl);
        return array("returnCode" => '500', 'message' => "Curl Error: " . $error);
    }

    // Close curl session
    curl_close($curl);

    $responseData = json_decode($response, true);
    
    $database_books = array();
    foreach ($responseData["items"] as $item) {
        $book_info = $item["volumeInfo"];

        $title = $book_info["title"];
        $authors = isset($book_info["authors"]) ? $book_info["authors"] : null;
        $genres = isset($book_info["categories"]) ? $book_info["categories"] : null;
        $languages = isset($book_info["language"]) ? $book_info["language"] : null;
        $year_published = isset($book_info["publishedDate"]) ? explode("-", $book_info["publishedDate"])[0] : null;
        $description = isset($book_info["description"]) ? $book_info["description"] : null;
        $cover_image_url = isset($book_info["imageLinks"]["thumbnail"]) ? $book_info["imageLinks"]["thumbnail"] : null;

        $book = array(
            "title" => $title,
            "authors" => $authors,
            "genres" => $genres,
            "languages" => $languages,
            "year_published" => $year_published,
            "description" => $description,
            "cover_image_url" => $cover_image_url
        );
        $database_books[] = $book;
    }

    return $database_books;
}

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);

    if ($request['type'] === "dmz_search"){
        $database_books = handleQuery($request);
        return array("returnCode" => '0', 'message' => $database_books);
    }
    
}

$server = new rabbitMQServer("RabbitMQ.ini", "server");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>