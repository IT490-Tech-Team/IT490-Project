<?php

function getDatabaseConnection()
{
    $host = 'localhost';
    $username = 'bookQuest';
    $password = '3394dzwHi0HJimrA13JO';
    $database = 'userdb';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        // Set PDO to throw exceptions on error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Log error or handle as needed
        return null;
    }
}

function addCoverImageUrls($booksWithUrls)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Array to store updated books
    $updatedBooks = array();

    // Loop through each book with cover image URL
    foreach ($booksWithUrls as $book) {
        $bookId = $book[0];
        $coverImageUrl = $book[1];

        try {
            // Prepare SQL statement to update cover image URL for the book
            $stmt = $conn->prepare("UPDATE books SET cover_image_url = ? WHERE id = ?");
            // Bind parameters
            $stmt->bind_param("si", $cover_image_url, $bookId);
            // Set parameters
            $cover_image_url = $coverImageUrl;
            // Execute the query
            if ($stmt->execute()) {
                // If query is successful, add book information to the array
                $updatedBooks[] = array("id" => $bookId, "cover_image_url" => $coverImageUrl);
            }
        } catch (Exception $e) {
            // Log error or handle as needed
            return array("returnCode" => 500, "message" => "Error updating cover image URL for book $bookId: " . $e->getMessage());
        }
    }

    // Close database connection
    $conn->close();

    // Return array of updated books
    return $updatedBooks;
}

?>
