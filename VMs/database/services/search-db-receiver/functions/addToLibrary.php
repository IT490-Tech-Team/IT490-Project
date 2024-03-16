<?php

function addToLibrary($userId, $bookId)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Check if the entry already exists in user_library
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM user_library WHERE user_id = ? AND book_id = ?");
        $stmt_check->bind_param("ii", $userId, $bookId);
        $stmt_check->execute();
        
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];
        $stmt_check->close();

        if ($count > 0) {
            // Close database connection
            $conn->close();
            return array("returnCode" => 400, "message" => "The book is already in the user's library");
        }

        // Prepare SQL statement to insert a new entry in user_library
        $stmt = $conn->prepare("INSERT INTO user_library (user_id, book_id) VALUES (?, ?)");

        // Bind parameters
        $stmt->bind_param("ii", $userId, $bookId);

        // Execute the query
        if ($stmt->execute()) {
            // Close statement
            $stmt->close();

            // Close database connection
            $conn->close();

            return array("returnCode" => 200, "message" => "Book added to user library successfully");
        } else {
            return array("returnCode" => 500, "message" => "Error adding book to user library");
        }
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error adding book to user library: " . $e->getMessage());
    }
}

?>