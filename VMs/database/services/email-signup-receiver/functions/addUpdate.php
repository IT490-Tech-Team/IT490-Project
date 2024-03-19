<?php

function addUpdate($user_id, $type, $query)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new update record
        $stmt = $conn->prepare("INSERT INTO updates (user_id, type, query) VALUES (?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("iss", $user_id, $type, $query);

        // Execute the query
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return success message
            return array("returnCode" => 200, "message" => "Update added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error inserting update");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error inserting update: " . $e->getMessage());
    }
}

?>
