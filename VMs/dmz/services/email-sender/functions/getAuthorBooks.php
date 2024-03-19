<?php

function fetchBooksByAuthor($author)
{
    // Google Books API URL
    $url = "https://www.googleapis.com/books/v1/volumes?q=author:" . urlencode($author);

    // Initialize cURL session
    $curl = curl_init();

    // Set cURL options
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HEADER, false);

    // Execute cURL request
    $response = curl_exec($curl);

    // Check for errors
    if ($response === false) {
        // Handle error
        echo "Error fetching books: " . curl_error($curl);
        return null;
    }

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $booksData = json_decode($response, true);

    // Check if data was successfully decoded
    if ($booksData === null) {
        // Handle error
        echo "Error decoding JSON data.";
        return null;
    }

    // Check if data was successfully decoded and items key exists
    if (isset($booksData["items"])) {
        // Return items
        return $booksData["items"];
    } else {
        // Return null if items key does not exist
        return null;
    }
}

?>

