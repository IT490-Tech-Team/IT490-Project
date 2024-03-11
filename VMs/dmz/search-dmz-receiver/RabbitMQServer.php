#!/usr/bin/php
<?php

// Include required files
require_once('dependencies/path.inc');
require_once('dependencies/get_host_info.inc');
require_once('dependencies/rabbitMQLib.inc');
require_once('functions/query.php');

// Function to handle the query and retrieve book information from Google Books API
function handleQuery($request)
{
    // Construct the URL
    $url = "https://www.googleapis.com/books/v1/volumes?q=" . urlencode($request["title"]);

    // Initialize curl session
    $curl = curl_init();

    // Set the curl options
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true, // Follow redirects
        CURLOPT_HEADER => false, // Exclude header from output
        CURLOPT_SSL_VERIFYPEER => false, // Disable SSL verification (use with caution)
        CURLOPT_USERAGENT => 'Your User Agent String Here', // Set a user agent string
    ]);

    // Execute the curl request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        $error = curl_error($curl);
        return ["returnCode" => 500, 'message' => "Curl Error: " . $error];
    }

    // Close curl session
    curl_close($curl);

    // Decode the JSON response
    $responseData = json_decode($response, true);

    // Initialize array to store book information
    $databaseBooks = [];

    // Process each book item in the response
    foreach ($responseData["items"] as $item) {
        $bookInfo = $item["volumeInfo"];

        // Extract relevant information
        $title = $bookInfo["title"];
        $authors = $bookInfo["authors"] ?? null;
        $genres = $bookInfo["categories"] ?? null;
        $languages = $bookInfo["language"] ?? null;
        $yearPublished = isset($bookInfo["publishedDate"]) ? explode("-", $bookInfo["publishedDate"])[0] : null;
        $description = $bookInfo["description"] ?? null;
        $coverImageUrl = $bookInfo["imageLinks"]["thumbnail"] ?? null;

        // Create book array
        $book = [
            "title" => $title,
            "authors" => $authors,
            "genres" => $genres,
            "languages" => $languages,
            "year_published" => $yearPublished,
            "description" => $description,
            "cover_image_url" => $coverImageUrl
        ];

        // Add book to the result array
        $databaseBooks[] = $book;
    }

    // Return array of book information
    return $databaseBooks;
}

// Function to process incoming requests
function requestProcessor($request)
{
    // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check request type
    if ($request['type'] === "dmz_search") {
        // Handle the search query
        $databaseBooks = handleQuery($request);
        // Return response
        return ["returnCode" => 0, 'message' => $databaseBooks];
    }
}

// Create RabbitMQ server instance
$server = new rabbitMQServer("RabbitMQ.ini", "development");

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>
