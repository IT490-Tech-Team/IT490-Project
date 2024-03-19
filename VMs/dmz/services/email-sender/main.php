<?php

require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
include_once("functions/getAuthorBooks.php");

function processUpdates($updates)
{
    
    // Check if updates array is empty
    if (empty($updates)) {
        echo "No updates found." . PHP_EOL;
        return;
    }
    
    
    // Iterate through each update
    foreach ($updates as $update) {
        $recipient = $update["user_email"];
        $email_books = array();

        // Check if the update type is "author"
        if ($update['type'] === 'author') {
            // Process author
            $email_books = processAuthor($update['query']);
        }

        if ($email_books !== null) {
            $message = prepareBookMessage($email_books);
            $subject = "New books from " . $update["query"];
            
            sendEmail($recipient, $subject, $message);
        }

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

function sendEmail($recipient, $subject, $body) {
    // Path to the send_email.sh script
    $scriptPath = "./email.sh";
    
    // Execute the Bash script with arguments
    $command = escapeshellcmd("$scriptPath $recipient \"$subject\" \"$body\"");
    $output = shell_exec($command);
    echo $output;
}

function processPublishedDate($publishedDate) {
    // Check if the published date contains only a year
    if (preg_match('/^\d{4}$/', $publishedDate)) {
        // If it's just a year, append January 1st to it
        $publishedDate .= '-01-01';
    }

    // Try parsing the modified date string
    $timestamp = strtotime($publishedDate);

    return $timestamp; // Add semicolon here
}

function prepareBookMessage($books) {
    $message = "Here are the books recently fetched:\n\n";
    foreach ($books as $book) {
        $authors = isset($book['authors']) ? $book['authors'] : 'Unknown Author';
        $publishedDate = isset($book['publishedDate']) ? $book['publishedDate'] : 'Unknown Date';
        $message .= "- " . $book['title'] . " by " . $authors . " (" . $publishedDate . ")\n";
    }
    return $message;
}

$request = array();
$request['type'] = "get_all_updates";

// Sends the message and waits for a response
$client = new rabbitMQClient("RabbitMQ.ini","development");
$response = $client->send_request($request);

$updates = $response['updates'];

processUpdates($updates);

// echo json_encode($response);
exit(0);
?>