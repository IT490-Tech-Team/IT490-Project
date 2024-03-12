<?php

// Function to save book covers
function saveBookCovers($bookCovers)
{
    // Array to store saved book covers
    $savedBookCovers = [];

    // Iterate over each book cover
    foreach ($bookCovers as $cover) {
        $bookId = $cover["id"];
        $imageUrl = $cover["cover_image_url"];

        // Get the image data
        $imageData = file_get_contents($imageUrl);
        if ($imageData === false) {
            // If image retrieval fails, continue with the next cover
            continue;
        }

        // Define the path to save the image
        $destinationPath = "/var/www/html/book_covers/{$bookId}.jpeg";

        // Save the image
        $bytesWritten = file_put_contents($destinationPath, $imageData);
        if ($bytesWritten === false) {
            // If image saving fails, continue with the next cover
            continue;
        }

        // Update the array with the new URL
        $newImageUrl = "/book_covers/{$bookId}.jpeg";
        $savedBookCovers[] = [$bookId, $newImageUrl];
    }

    return array("returnCode" => 200, "message" => $savedBookCovers);
}

?>