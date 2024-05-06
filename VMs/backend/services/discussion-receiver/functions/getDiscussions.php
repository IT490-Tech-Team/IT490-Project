<?php

require_once("createLog.php");

function getDiscussionByBookId($book_id)
{
	/* log data */ $log_path = "backend/services/discussion-receiver/functions/getDiscussions.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select discussions filtered by book_id
        /* log */ createLog("Info", "Retreiving discussions where book_id=".$book_id, $log_path);
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
            /* log */ createLog("Info", "Successfully retreived discussions where book_id=".$book_id, $log_path);
            return array("returnCode" => 200, "discussions" => $discussions);
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            /* log */ createLog("Error", "Error fetching discussions where book_id=".$book_id, $log_path);
            return array("returnCode" => 500, "message" => "Error fetching discussions");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error fetching discussions where book_id=".$book_id, $log_path);
        return array("returnCode" => 500, "message" => "Error fetching discussions: " . $e->getMessage());
    }
}

?>
