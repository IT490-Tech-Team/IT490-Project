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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <link rel="stylesheet" href="base.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav>
        <div id="logo"><a href="/">Online Bookshelf</a></div>
        <div id="links">
            <a href="search.html">Search</a>
            <a href="/login/">Login</a>
            <a href="/register/">Register</a>
        </div>
    </nav>
    <section id="welcome-banner"></section>

    <div class="container">
        <?php
        // Retrieve the book ID from the query string
        $bookId = $_GET['id']; // Assuming 'id' is the parameter containing the book ID

        // Prepare SQL query to retrieve details of the selected book
        $sql = "SELECT * FROM books WHERE id = $bookId"; // Assuming 'books' is the name of your table

        // Execute the query
        $result = $conn->query($sql);

        // Check if a book with the specified ID exists
        if ($result->num_rows > 0) {
            // Book details found, display them
            $bookDetails = $result->fetch_assoc();
            echo "<h1>" . $bookDetails['title'] . "</h1>";
            echo "<p>Author: " . $bookDetails['author'] . "</p>";
            echo "<p>Subject: " . $bookDetails['subject_facet'] . "</p>";
            // Add more details as required
        } else {
            // Book not found
            echo "Book not found.";
        }
        ?>
    </div>

    <div class="container">
        <a href="/search.html" class="button">Return to Search</a>
    </div>

    <script src="main.js"></script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
