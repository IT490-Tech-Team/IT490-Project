<?php

function getDiscussionById($discussionId)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select the discussion with the given ID
        $stmt = $conn->prepare("SELECT * FROM discussions WHERE id = ?");
        $stmt->bind_param("i", $discussionId);

        // Execute the query
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Fetch the discussion row as an associative array
            $discussion = $result->fetch_assoc();

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Check if discussion row exists
            if ($discussion) {
                return array("returnCode" => 200, "discussion" => $discussion);
            } else {
                return array("returnCode" => 404, "message" => "Discussion not found");
            }
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            return array("returnCode" => 500, "message" => "Error fetching discussion");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error fetching discussion: " . $e->getMessage());
    }
}

?>
