<?php

require_once("createLog.php");

function getDiscussionByDiscussionId($discussionId)
{
	/* log data */ $log_path = "backend/services/discussion-receiver/functions/getDiscussionById.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select the discussion with the given ID
        /* log */ createLog("Info", "Retreiving discussion where discussionId=".$discussionId, $log_path);
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
	            /* log */ createLog("Info", "Successfully retreived discussion where discussionId=".$discussionId, $log_path);
                return array("returnCode" => 200, "discussion" => $discussion);
            } else {
	            /* log */ createLog("Error", "Discussion not found where discussionId=".$discussionId, $log_path);
                return array("returnCode" => 404, "message" => "Discussion not found");
            }
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            /* log */ createLog("Error", "Error fetching discussion where discussionId=".$discussionId, $log_path);
            return array("returnCode" => 500, "message" => "Error fetching discussion");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error fetching discussion where discussionId=".$discussionId.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error fetching discussion: " . $e->getMessage());
    }
}

?>
