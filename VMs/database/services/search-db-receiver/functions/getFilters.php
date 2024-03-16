<?php

function getFilters() {
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Query distinct genres and languages
    $sql = "SELECT DISTINCT genres, languages FROM books";
    $result = $conn->query($sql);

    if ($result === false) {
        // Error executing the query
        $conn->close();
        return array("returnCode" => 500, "message" => "Error executing query");
    }

    // Fetch genres and languages
    $genres = array();
    $languages = array();
    while ($row = $result->fetch_assoc()) {
        $genresArray = json_decode($row['genres'], true);
        $languages[] = $row['languages'];
        foreach ($genresArray as $genre) {
            if (!in_array($genre, $genres)) {
                $genres[] = $genre;
            }
        }
    }

    // Close database connection
    $conn->close();

    // Return genres and languages
    return array(
        "returnCode" => 200,
        "genres" => $genres,
        "languages" => array_unique($languages)
    );
}

?>
