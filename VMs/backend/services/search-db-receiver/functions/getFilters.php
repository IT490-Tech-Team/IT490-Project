<?php

require_once("createLog.php");

function getFilters() 
{
	/* log data */ $log_path = "backend/services/search-db-receiver/functions/getFilters.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    // Query distinct genres and languages
    /* log */ createLog("Info", "Querying distinct genres and languages", $log_path);
    $sql = "SELECT DISTINCT genres, languages FROM books";
    $result = $conn->query($sql);

    if ($result === false) {
        // Error executing the query
        $conn->close();
        /* log */ createLog("Error", "Error executing query", $log_path);
        return array("returnCode" => 500, "message" => "Error executing query");
    }

    // Fetch genres and languages
    $genres = array();
    $languages = array();
    while ($row = $result->fetch_assoc()) {
        $genresArray = json_decode($row['genres'], true) ?? array();
        $languages[] = $row['languages'];
        foreach ($genresArray as $genre) {
            if (!in_array($genre, $genres)) {
                $genres[] = $genre;
            }
        }
    }

    // Close database connection
    $conn->close();
    
    /* log */ createLog("Info", "Successfully filtered results", $log_path);

    // Return genres and languages
    return array(
        "returnCode" => 200,
        "genres" => $genres,
        "languages" => array_unique($languages)
    );
}

?>
