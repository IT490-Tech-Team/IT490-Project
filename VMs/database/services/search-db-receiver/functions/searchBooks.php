<?php

function searchBooks($title, $author, $genre, $language, $year)
{
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Initialize array to hold search parameters
    $params = array();
    $sortingConditions = array();

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
        return array("returnCode" => 200, "message" => $books);
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
