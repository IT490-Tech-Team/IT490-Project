<?php

require_once("createLog.php");

function addUpdate($user_email, $type, $query)
{
	/* log data */ $log_path = "backend/services/email-signup-receiver/functions/addUpdate.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new update record
        /* log */ createLog("Info", "Updating ".$user_email." where type=".$type." and query=".$query, $log_path);
        $stmt = $conn->prepare("INSERT INTO updates (user_email, type, query) VALUES (?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("sss", $user_email, $type, $query);

        // Execute the query
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return success message
        	/* log */ createLog("Info", "Update added successfully to ".$user_email, $log_path);
            return array("returnCode" => 200, "message" => "Update added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
        	/* log */ createLog("Error", "Error inserting update to ".$user_email, $log_path);
            return array("returnCode" => 500, "message" => "Error inserting update");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
       	/* log */ createLog("Error", "Error inserting update to ".$user_email.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error inserting update: " . $e->getMessage());
    }
}

?>
