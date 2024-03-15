<?php

function getBooks($bookIds)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement
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
            return array("returnCode" => 200, "message" => $books);
        } else {
            // No results found
            // Close statement
            $stmt->close();
            // Close database connection
            $conn->close();
            return array("returnCode" => 404, "message" => []);
        }
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error retrieving books: " . $e->getMessage());
    }
}

?>
