<?php
// Decode the JSON data sent from the HTML file
$data = json_decode(file_get_contents('php://input'), true);

// Extract user email from the data
$email = $data['email']; // Access email directly

// Extract books array from the data
$books = $data['books'];

// Email parameters
$to = $email;
$subject = "Monthly Update Email - Test";
$headers = "From: bookquestit490@gmail.com";

// Email message with books array included
$message = "Dear User,\n\n";
$message .= "Here are the books recently fetched:\n\n";
foreach ($books as $book) {
    $message .= "- " . $book['title'] . " by " . implode(', ', $book['authors']) . " (" . $book['publishedDate'] . ")\n";
}

// Send email
if (mail($to, $subject, $message, $headers)) {
    echo json_encode(array('status' => 'success'));
} else {
    echo json_encode(array('status' => 'error', 'message' => 'Failed to send email.'));
}
?>
