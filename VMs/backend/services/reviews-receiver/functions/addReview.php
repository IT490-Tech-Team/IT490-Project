<?php

function addReview($book_id, $user_id, $username, $rating, $comment)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new review record
        $stmt = $conn->prepare("INSERT INTO reviews (book_id, user_id, username, rating, comment) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("iisis", $book_id, $user_id, $username, $rating, $comment);

        // Execute the query
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return success message
            return array("returnCode" => 200, "message" => "Review added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error inserting review");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error inserting review: " . $e->getMessage());
    }
}

?>

