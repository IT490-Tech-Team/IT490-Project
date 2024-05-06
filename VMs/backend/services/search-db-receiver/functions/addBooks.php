<?php

require_once("createLog.php");

function addBooks($books)
{
	/* log data */ $bcount = count($books);
	/* log data */ $log_path = "backend/services/search-db-receiver/functions/addBooks.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }
    
    
    /* log */ createLog("Info", "Inserting ".$bcount." books into the database", $log_path);

    // Array to store the IDs of inserted books along with their cover image URLs
    $insertedBooks = array();

    // Iterate over each book in the request
    foreach ($books as $book) {
        try {
            // Prepare SQL statement to insert a new book record
            $stmt = $conn->prepare("INSERT INTO books (title, authors, genres, languages, year_published, description, cover_image_url) VALUES (?, ?, ?, ?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param("ssssiss", $title, $authors, $genres, $languages, $year_published, $description, $cover_image_url);

            // Set parameters
            $title = $book['title'];
            $authors = json_encode($book['authors']);
            $genres = json_encode($book['genres']);
            $languages = $book['languages'];
            $year_published = $book['year_published'];
            $description = $book['description'];
            $cover_image_url = $book['cover_image_url'];

            // Execute the query
            if ($stmt->execute()) {
                // If query is successful, retrieve the ID of the last inserted record
                $last_insert_id = $stmt->insert_id;
                // Add the ID and cover image URL to the result array
                $insertedBooks[] = array(
                    "id" => $last_insert_id,
                    "title" => $title,
                    "authors" => json_decode($authors, true),
                    "genres" => json_decode($genres, true),
                    "languages" => $languages,
                    "year_published" => $year_published,
                    "description" => $description,
                    "cover_image_url" => $cover_image_url
                );
            }
            /* log */ createLog("Info", "Inserted '".$title."' into the database", $log_path);
            
        } catch (Exception $e) {
            // Log error or handle as needed
            /* log */ createLog("Error", "Error inserting ".$bcount." books into the database: ".$e->getMessage(), $log_path);
            return array("returnCode" => 500, "books" => "Error inserting book: " . $e->getMessage());
        }
    }

    // Close database connection
    $conn->close();

    // Return array containing IDs of inserted books along with their cover image URLs
     /* log */ createLog("Error", "Successfully inserted ".$bcount." books", $log_path);
    return array("returnCode" => 200, "books" => $insertedBooks);

}

?>
