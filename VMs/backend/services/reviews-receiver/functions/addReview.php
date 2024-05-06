<?php

require_once("createLog.php");

function addReview($book_id, $user_id, $username, $rating, $comment)
{
	/* log data */ $log_path = "backend/services/reviews-receiver/functions/addReview.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to insert a new review record
        /* log */ createLog("Info", "Adding review from ".$username." where book_id=".$book_id, $log_path);
        /* log */ createLog("Info", "Review by ".$username." is a ".$rating." and commented '".$comment."'", $log_path);
        $stmt = $conn->prepare("INSERT INTO reviews (book_id, user_id, username, rating, comment) VALUES (?, ?, ?, ?, ?)");

        // Bind parameters
        $stmt->bind_param("iisis", $book_id, $user_id, $username, $rating, $comment);

        // Execute the query
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return success message
        	/* log */ createLog("Info", "Successfully added review by ".$username." where book_id=".$book_id, $log_path);
            return array("returnCode" => 200, "message" => "Review added successfully");
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
        	/* log */ createLog("Error", "Error inserting review by ".$username." where book_id=".$book_id, $log_path);
            return array("returnCode" => 500, "message" => "Error inserting review");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error inserting review by ".$username." where book_id=".$book_id.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error inserting review: " . $e->getMessage());
    }
}

?>

