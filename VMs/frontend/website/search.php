<?php
// Establish a connection to the MySQL database
$servername = "localhost";
$username = "tester"; // Your MySQL username
$password = "12345"; // Your MySQL password
$dbname = "booksdb"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the search term and search type from the request
$searchTerm = $_POST['searchTerm'];
$searchType = $_POST['searchType'];

// Prepare the search query based on the search type
if ($searchType === 'title') {
    $sql = "SELECT * FROM books WHERE title LIKE '%$searchTerm%'";
} elseif ($searchType === 'author') {
    $sql = "SELECT * FROM books WHERE author LIKE '%$searchTerm%'";
} elseif ($searchType === 'subject') {
    $sql = "SELECT * FROM books WHERE subject_facet LIKE '%$searchTerm%'";
}

// Perform the search query
$result = $conn->query($sql);

// Process and return the search results
$searchResults = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }
}

// Return the search results as JSON
echo json_encode($searchResults);

// Close the database connection
$conn->close();
?>
