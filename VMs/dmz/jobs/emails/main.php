<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require_once('rabbitMQLib.inc');
require_once("functions/author.php");

// Get Path of JSON, Read JSON, Decode JSON
$json_file = 'environment.json';
$json_data = file_get_contents($json_file);
$settings = json_decode($json_data, true);

// Set the RABBITMQ_HOST variable from the current environment
if (isset ($settings['currentEnvironment']) && isset ($settings[$settings['currentEnvironment']]['BROKER_HOST'])) {
  $BROKER_HOST = $settings[$settings['currentEnvironment']]['BROKER_HOST'];
} else {
  // Set default value if there's an error or if the variable is null
  $BROKER_HOST = '127.0.0.1';
}

function sendEmail($recipientEmail, $subject, $body)
{
    $passwords = json_decode(file_get_contents('password.json'), true);

    $mail = new PHPMailer();

    try {
        // SMTP configuration for Gmail
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bookquestit490@gmail.com'; // Your Gmail address
        $mail->Password = $passwords['password']; // Your Gmail password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Sender and recipient settings
        $mail->setFrom('bookquestit490@gmail.com', 'BookQuest Team');
        $mail->addAddress($recipientEmail);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        // Send email
        if ($mail->send()) {
            echo 'Email has been sent successfully to ' . $recipientEmail . '!';
        } else {
            echo 'Error: ' . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
    }
}

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
            // Process author -- functions/Author.php
            $email_books = processAuthor($update['query']);
        }

        if ($email_books !== null) {
            $message = prepareBookMessage($email_books);
            $subject = "BookQuest: New Books Notification";
            
            echo $message;

            // Call the sendEmail function with appropriate arguments
            sendEmail($recipient, $subject, $message);
        }
    }
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
    $message = "<div>";
    $message .= "<h1>Look forward to these books:</h1>";
    $message .= "<ul>";
    foreach ($books as $book) {
        $authors = isset($book['authors']) ? $book['authors'] : 'Unknown Author';
        $publishedDate = isset($book['publishedDate']) ? $book['publishedDate'] : 'Unknown Date';

        $message .= "<li>" . $book['title'] . " by " . $authors . " (" . $publishedDate . ")" . "</li>";
    }
    $message .= "</ul>";
    $message .= "</div>";
    return $message;
}

$connectionConfig = [
    "BROKER_HOST" => $BROKER_HOST,
    "BROKER_PORT" => 5672,
    "USER" => "bookQuest",
    "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
    "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
    "EXCHANGE_TYPE" => "topic",
    "AUTO_DELETE" => true,
    "EXCHANGE" => "emailExchange",
    "QUEUE" => "emailQueue",
];


$client = new rabbitMQClient($connectionConfig, $exchangeQueueConfig);

$request = array();
$request['type'] = "get_all_updates";
$response = $client->send_request($request);

$updates = $response['updates'];

processUpdates($updates);

?>