<?php

require_once("createLog.php");

function searchBooks($title, $author, $genre, $language, $year)
{
	/* log data */ $log_path = "backend/services/search-db-receiver/functions/searchBooks.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Initialize array to hold search parameters
    $params = array();
    $sortingConditions = array();
    
    
    /* log */ createLog("Info", "Seaching for books: ".$title.", ".$author.", ".$genre.", ".$language.", ".$year, $log_path);

    // Prepare base SQL statement
    $sql = "SELECT * FROM books WHERE 1=1";

    // Add title search criteria if provided
    if ($title !== null && strlen($title) > 0) {
        $sql .= " AND title LIKE ?";
        $params[] = "%" . $title . "%";
        $sortingConditions[] = "title DESC";
    }

    // Add additional search criteria if provided
    if ($author !== null && strlen($author) > 0) {
        $sql .= " AND authors LIKE ?";
        $params[] = "%" . $author . "%";
        $sortingConditions[] = "authors DESC";
    }
    if ($genre !== null && strlen($genre) > 0) {
        $sql .= " AND genres LIKE ?";
        $params[] = "%" . $genre . "%";
        $sortingConditions[] = "genres DESC";
    }
    if ($language !== null && strlen($language) > 0) {
        $sql .= " AND languages LIKE ?";
        $params[] = "%" . $language . "%";
        $sortingConditions[] = "languages DESC";
    }
    if ($year !== null && strlen($year) > 0) {
        $sql .= " AND year_published LIKE ?";
        $params[] = "%" . $year . "%";
        $sortingConditions[] = "year_published DESC";
    }

    if (!empty($sortingConditions)) {
        $sql .= " ORDER BY " . implode(", ", $sortingConditions);
    }

    echo $sql;

    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    // Dynamically bind parameters
    $types = str_repeat("s", count($params));
    if ($types !== "") {
        $stmt->bind_param($types, ...$params);
    }

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
    	/* log */ createLog("Info", "Found and returning ".$result->num_rows." books", $log_path);
        return array("returnCode" => 200, "books" => $books);
    } else {
        // No results found
        // Close statement
        $stmt->close();
        // Close database connection
        $conn->close();
        /* log */ createLog("Error", "No books found", $log_path);
        return array("returnCode" => 200, "books" => []);
    }
}

?>
