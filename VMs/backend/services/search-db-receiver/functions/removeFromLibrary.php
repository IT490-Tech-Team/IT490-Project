<?php

require_once("createLog.php");

function removeFromLibrary($userId, $bookId)
{
	/* log data */ $log_path = "backend/services/search-db-receiver/functions/removeFromLibrary.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Check if the entry exists in user_library
    	/* log */ createLog("Info", "Removing book to user library where bookId=".$bookId." and userId=".$userId, $log_path);
        $stmt_check = $conn->prepare("SELECT COUNT(*) AS count FROM user_library WHERE user_id = ? AND book_id = ?");
        $stmt_check->bind_param("ii", $userId, $bookId);
        $stmt_check->execute();
        
        $result_check = $stmt_check->get_result();
        $count = $result_check->fetch_assoc()['count'];
        $stmt_check->close();

        if ($count == 0) {
            // Close database connection
            $conn->close();
	    	/* log */ createLog("Alert", "Book not in user library where bookId=".$bookId." and userId=".$userId, $log_path);
            return array("returnCode" => 400, "message" => "The book is not in the user's library");
        }

        // Prepare SQL statement to remove the entry from user_library
        $stmt = $conn->prepare("DELETE FROM user_library WHERE user_id = ? AND book_id = ?");

        // Bind parameters
        $stmt->bind_param("ii", $userId, $bookId);

        // Execute the query
        if ($stmt->execute()) {
            // Close statement
            $stmt->close();

            // Close database connection
            $conn->close();

			/* log */ createLog("Info", "Successfully removed book to user library where bookId=".$bookId." and userId=".$userId, $log_path);
            return array("returnCode" => 200, "message" => "Book removed from user library successfully");
        } else {
            /* log */ createLog("Error", "Error removing book to user library where bookId=".$bookId." and userId=".$userId, $log_path);
            return array("returnCode" => 500, "message" => "Error removing book from user library");
        }
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error adding book to user library where bookId=".$bookId." and userId=".$userId.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error removing book from user library: " . $e->getMessage());
    }
}

?>
