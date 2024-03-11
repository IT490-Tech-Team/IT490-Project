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

function addBooks($request)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Decode the JSON string containing book data
    $books = json_decode($request["books"], true);

    // Array to store the IDs of inserted books along with their cover image URLs
    $insertedBooks = array();

    // Iterate over each book in the request
    foreach ($books as $book) {
        try {
            // Prepare SQL statement to insert a new book record
            $stmt = $conn->prepare("INSERT INTO books (title, authors, genres, languages, year_published, description, cover_image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("ssssiss", $title, $authors, $genres, $languages, $year_published, $description, $cover_image_url);

            // Set parameters
            $title = $book['title'];
            $authors = json_encode($book['authors']);
            $genres = json_encode($book['genres']);
            $languages = $book['languages'];
            $year_published = $book['year_published'];
            $description = $book['description'];
            $cover_image_url = $book['cover_image_url'];

            // Execute the query
            if ($stmt->execute()) {
                // If query is successful, retrieve the ID of the last inserted record
                $last_insert_id = $stmt->insert_id;
                // Add the ID and cover image URL to the result array
                $insertedBooks[] = array("id" => $last_insert_id, "cover_image_url" => $cover_image_url);
            }
        } catch (Exception $e) {
            // Log error or handle as needed
            return array("returnCode" => 500, "message" => "Error inserting book: " . $e->getMessage());
        }
    }

    // Close database connection
    $conn->close();

    // Return array containing IDs of inserted books along with their cover image URLs
    return $insertedBooks;
}

?>
