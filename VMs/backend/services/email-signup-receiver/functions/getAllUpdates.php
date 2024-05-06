<?php

require_once("createLog.php");

function getAllUpdates()
{
	/* log data */ $log_path = "backend/services/email-signup-receiver/functions/getAllUpdates.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select all updates
        /* log */ createLog("Info", "Retreiving email updates", $log_path);
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
        	/* log */ createLog("Info", "Successfully retreived email updates", $log_path);
            return array("returnCode" => 200, "updates" => $updates);
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
        	/* log */ createLog("Error", "Error fetching email updates", $log_path);
            return array("returnCode" => 500, "message" => "Error fetching updates");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error fetching email updates: ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error fetching updates: " . $e->getMessage());
    }
}

?>
