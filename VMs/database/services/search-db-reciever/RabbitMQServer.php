#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function addBooks($request)
{
  try {
    $conn = new mysqli('localhost', 'bookQuest', '3394dzwHi0HJimrA13JO', 'booksdb');
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error connecting to the database"
    );
    return $response;
  }

  $books = json_decode($request["books"], true);
  
  $bookReturn = array();
  foreach ($books as $book) {
    $stmt = $conn->prepare("INSERT INTO books (title, authors, genres, languages, year_published, description) VALUES (?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $stmt->bind_param("ssssis", $title, $authors, $genres, $languages, $year_published, $description);

    // Set parameters
    $title = $book['title'];
    $authors = json_encode($book['authors']);
    $genres = json_encode($book['genres']);
    $languages = $book['languages'];
    $year_published = $book['year_published'];
    $description = $book['description'];
    $cover_image_url = $book['cover_image_url'];

    // Execute the query
    if ($stmt->execute() === TRUE) {
      $last_insert_id = $stmt->insert_id; // Retrieve the ID of the last inserted record
      $bookReturn[] = [$last_insert_id, $cover_image_url];
    }
  }

  return $bookReturn;

}

function addCoverImageUrls($booksWithUrls)
{
    try {
        $conn = new mysqli('localhost', 'bookQuest', '3394dzwHi0HJimrA13JO', 'booksdb');
    } catch (Exception $e) {
        $response = array(
            "returnCode" => '500',
            "message" => "Error connecting to the database"
        );
        return $response;
    }

    $bookReturn = array();
    foreach ($booksWithUrls as $book) {
        $bookId = $book[0];
        $coverImageUrl = $book[1];

        $stmt = $conn->prepare("UPDATE books SET cover_image_url = ? WHERE id = ?");

        // Bind parameters
        $stmt->bind_param("si", $cover_image_url, $bookId);

        // Set parameters
        $cover_image_url = $coverImageUrl;

        // Execute the query
        if ($stmt->execute() === TRUE) {
            $bookReturn[] = [$bookId, $coverImageUrl];
        }
    }

    return $bookReturn;
}

function requestProcessor($request)
{
    echo "received request" . PHP_EOL;
    var_dump($request);

    if (!isset($request['type'])) {
      return "ERROR: unsupported message type";
    }

    if ($request['type'] === "add") {
      $books = addBooks($request);
      return array("returnCode" => '200', 'message' => $books);
    }
    elseif ($request['type'] === "add_covers") {
      $books = $request["books"];
      $database_books = addCoverImageUrls(json_decode($books, true));
      return array("returnCode" => '200', 'message' => $database_books);
    }

    return array("returnCode" => '0', 'message' => "");
}

$server = new rabbitMQServer("RabbitMQ.ini", "development");

echo "testRabbitMQServer BEGIN" . PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;

?>