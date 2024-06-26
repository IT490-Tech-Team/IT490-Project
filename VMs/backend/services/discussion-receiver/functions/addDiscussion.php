<?php

require_once("createLog.php");

function addDiscussion($book_id, $user_id, $username, $comment, $reply_to_id = null)
{
	/* log data */ $log_path = "backend/services/discussion-receiver/functions/addDiscussion.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new discussion record
        /* log */ createLog("Info", "Adding discussion from ".$username." where book_id=".$book_id, $log_path);
        /* log */ createLog("Info", $username." commented '".$comment."'", $log_path);
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
            /* log */ createLog("Info", "Discussion by ".$username." added successfully", $log_path);
            return array("returnCode" => 200, "message" => "Discussion added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
            /* log */ createLog("Error", "Error inserting discussion by ".$username." into database", $log_path);
            return array("returnCode" => 500, "message" => "Error inserting discussion");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error inserting discussion by ".$username." into database: ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error inserting discussion: " . $e->getMessage());
    }
}

?>
