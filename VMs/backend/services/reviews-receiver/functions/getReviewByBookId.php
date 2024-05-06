 <?php

require_once("createLog.php");

function getReviewsByBookId($book_id)
{
	/* log data */ $log_path = "backend/services/reviews-receiver/functions/getReviewByBookId.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to select reviews for the given book ID
        /* log */ createLog("Info", "Retreiving reviews where book_id=".$book_id, $log_path);
        $stmt = $conn->prepare("SELECT * FROM reviews WHERE book_id = ?");
        $stmt->bind_param("i", $book_id);

        // Execute the query
        if ($stmt->execute()) {
            // Get result set
            $result = $stmt->get_result();

            // Fetch all review rows as an associative array
            $reviews = $result->fetch_all(MYSQLI_ASSOC);

            // Close the result set
            $result->close();

            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Check if reviews exist for the book ID
            if ($reviews) {
	            /* log */ createLog("Info", "Successfully retreived reviews where book_id=".$book_id, $log_path);
                return array("returnCode" => 200, "reviews" => $reviews);
            } else {
	            /* log */ createLog("Alert", "No reviews found where book_id=".$book_id, $log_path);
                return array("returnCode" => 404, "message" => "No reviews found for this book ID");
            }
        } else {
            // Close the statement
            $stmt->close();

            // Close database connection
            $conn->close();

            // Return error message
	        /* log */ createLog("Error", "Error fetching reviews where book_id=".$book_id, $log_path);
            return array("returnCode" => 500, "message" => "Error fetching reviews");
        }
    } catch (Exception $e) {
        // Log error or handle as needed
	    /* log */ createLog("Error", "Error fetching reviews where book_id=".$book_id.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error fetching reviews: " . $e->getMessage());
    }
}

?>

