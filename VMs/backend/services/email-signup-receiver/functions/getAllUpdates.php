<?php

function getAllUpdates()
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select all updates
        $stmt = $conn->prepare("SELECT * FROM updates ORDER BY created_at DESC");

        // Execute the query
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Fetch all updates as an associative array
            $updates = $result->fetch_all(MYSQLI_ASSOC);

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return updates
            return array("returnCode" => 200, "updates" => $updates);
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error fetching updates");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error fetching updates: " . $e->getMessage());
    }
}

?>
