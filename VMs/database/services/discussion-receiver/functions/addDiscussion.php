<?php

function addDiscussion($book_id, $user_id, $username, $comment, $reply_to_id = null)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new discussion record
        $stmt = $conn->prepare("INSERT INTO discussions (book_id, user_id, username, comment, reply_to_id) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        if ($reply_to_id !== null) {
            $stmt->bind_param("iissi", $book_id, $user_id, $username, $comment, $reply_to_id);
        } else {
            $stmt->bind_param("iissi", $book_id, $user_id, $username, $comment, $null);
        }

        // Execute the query
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return success message
            return array("returnCode" => 200, "message" => "Discussion added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error inserting discussion");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error inserting discussion: " . $e->getMessage());
    }
}

?>
