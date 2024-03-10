<?php

// MySQL database connection parameters
$servername = "localhost";
$username = "tester";
$password = "12345";
$database = "booksdb";

// Connect to MySQL database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch data from Open Library API
function fetch_books($query, $limit) {
    $url = 'http://openlibrary.org/search.json?q=' . urlencode($query) . '&limit=' . $limit;
    $response = file_get_contents($url);
    if ($response !== FALSE) {
        $data = json_decode($response, true);
        return isset($data['docs']) ? $data['docs'] : [];
    } else {
        echo 'Failed to fetch data from API';
        return [];
    }
}

// Function to sanitize and escape data for SQL queries
function sanitize($conn, $value) {
    return $conn->real_escape_string($value);
}

// Function to insert the titles and authors of 10 books into MySQL database
function insert_ten_book_titles_and_authors($query, $conn) {
    $books = fetch_books($query, 10);
    if (!empty($books)) {
        foreach ($books as $book) {
            $title = sanitize($conn, $book['title'] ?? '');
            $author = implode(', ', $book['author_name'] ?? []);
	    $subject_facet = implode(', ', $book['subject_facet'] ?? []);
            //$subject_facet = sanitize($conn, $book['subject_facet'] ?? '');
            $author = sanitize($conn, $author);
            $subject_facet = sanitize($conn, $subject_facet); 
            $sql = "INSERT INTO books (title, author, subject_facet) VALUES ('$title', '$author', '$subject_facet')";
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully: Title - $title, Author - $author<br>";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    } else {
        echo "No books found";
    }
}

// Insert the titles and authors of 10 books related to 'harry'
insert_ten_book_titles_and_authors('harry', $conn);

// Close MySQL connection
$conn->close();

?>
