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

function processAuthor($author)
{
    // Fetch books by author
    $books = fetchBooksByAuthor($author);
    $email_books = array();

    // Check if books are fetched successfully
    if ($books !== null) {
        // Display fetched books
        echo "Books by $author:" . PHP_EOL;
        foreach ($books as $book) {
            // Accessing title, authors, and publishedDate directly
            $title = $book["volumeInfo"]["title"];
            $authors = isset($book["volumeInfo"]["authors"]) ? implode(", ", $book["volumeInfo"]["authors"]) : '';
            $publishedDate = isset($book["volumeInfo"]["publishedDate"]) ? $book["volumeInfo"]["publishedDate"] : '';

            // Check if published date is after today's date
            $publishedDateTime = processPublishedDate($publishedDate);
            if ($publishedDateTime > strtotime('today')) {
                // If so, add the book to the email_books array
                $email_books[] = array(
                    "title" => $title,
                    "authors" => $authors,
                    "publishedDate" => $publishedDate
                );
            }
        }
        // Return the email_books array if books are found
        return $email_books;
    } else {
        // Handle case where no books are found or error occurs
        echo "No books found for author: $author" . PHP_EOL;
        return null;
    }
}
