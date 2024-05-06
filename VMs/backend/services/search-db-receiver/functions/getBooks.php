<?php

require_once("createLog.php");

function getBooks($bookIds)
{
	/* log data */ $bcount = count($bookIds)
	/* log data */ $log_path = "backend/services/search-db-receiver/functions/getBooks.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement
        /* log */ createLog("Info", "Retrieving ".$bcount." books", $log_path);
        $sql = "SELECT * FROM books WHERE id IN (" . implode(",", $bookIds) . ")";
        $stmt = $conn->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if there are any results
        if ($result->num_rows > 0) {
            // Fetch results as an associative array
            $books = $result->fetch_all(MYSQLI_ASSOC);
            // Close statement
            $stmt->close();
            // Close database connection
            $conn->close();
            /* log */ createLog("Info", "Successfully retrieved ".$result->num_rows." books", $log_path);
            return array("returnCode" => 200, "books" => $books);
        } else {
            // No results found
            // Close statement
            $stmt->close();
            // Close database connection
            $conn->close();
            /* log */ createLog("Alert", "No books found", $log_path);
            return array("returnCode" => 200, "books" => []);
        }
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error retrieving books", $log_path);
        return array("returnCode" => 500, "message" => "Error retrieving books: " . $e->getMessage());
    }
}

?>
