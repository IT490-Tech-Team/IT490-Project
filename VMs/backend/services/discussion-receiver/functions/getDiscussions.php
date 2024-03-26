<?php

function getDiscussionByBookId($book_id)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select discussions filtered by book_id
        $stmt = $conn->prepare("SELECT * FROM discussions WHERE book_id = ? ORDER BY created_at DESC");
        
        // Bind book_id parameter
        $stmt->bind_param("i", $book_id);

        // Execute the query
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Fetch all discussions as an associative array
            $discussions = $result->fetch_all(MYSQLI_ASSOC);

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return discussions
            return array("returnCode" => 200, "discussions" => $discussions);
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error fetching discussions");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error fetching discussions: " . $e->getMessage());
    }
}

?>
