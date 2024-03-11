<?php

function searchBooks($title, $author, $genre, $language, $year)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Prepare base SQL statement for fuzzy search on title
    $sql = "SELECT * FROM books WHERE title LIKE ?";

    // Bind parameter for title search
    $searchParam = "%" . $title . "%";

    // Add additional search criteria if provided
    if ($author !== null) {
        $sql .= " AND authors LIKE ?";
        $searchParam .= "%" . $author . "%";
    }
    if ($genre !== null) {
        $sql .= " AND genres LIKE ?";
        $searchParam .= "%" . $genre . "%";
    }
    if ($language !== null) {
        $sql .= " AND languages LIKE ?";
        $searchParam .= "%" . $language . "%";
    }
    if ($year !== null) {
        $sql .= " AND year_published LIKE ?";
        $searchParam .= "%" . $year . "%";
    }

    // Prepare SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameter
    $stmt->bind_param("s", $searchParam);

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
        return $books;
    } else {
        // No results found
        // Close statement
        $stmt->close();
        // Close database connection
        $conn->close();
        return array("returnCode" => 404, "message" => []);
    }
}

?>
