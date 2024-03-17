<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "tester"; // Replace with your MySQL username
$password = "12345"; // Replace with your MySQL password
$dbname = "bookShelf"; // Replace with your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch books from the Google Books API based on the author (Percival Everett) and filter by published date
function fetchBooksFromGoogleBooksAPI() {
    // Initialize an empty array to store book data
    $books = array();

    // Google Books API query to fetch books related to the author 
    //Placeholder: (Percival Everett)
    $google_books_api_url = 'https://www.googleapis.com/books/v1/volumes?q=author:percival-everett';
    $google_books_response = file_get_contents($google_books_api_url);

    // Check if the response is successful
    if ($google_books_response !== false) {
        // Decode the JSON response
        $books_data = json_decode($google_books_response, true);

        // Check if books data is not empty
        if (!empty($books_data['items'])) {
            // Extract relevant information about each book
            foreach ($books_data['items'] as $book_item) {
                $published_date = date('Y-m-d', strtotime($book_item['volumeInfo']['publishedDate']));

                // Add the book to the array if it was published in the current month
                if ($published_date >= date('Y-m-01') && $published_date <= date('Y-m-t')) {
                    $book = array(
                        'title' => $book_item['volumeInfo']['title'],
                        'authors' => $book_item['volumeInfo']['authors'],
                        'publishedDate' => $published_date
                        // Add more book information as needed
                    );

                    // Push book data into the books array
                    $books[] = $book;
                }
            }
        }
    } else {
        echo "Error fetching books from Google Books API";
    }

    return $books;
}

// Function to fetch users signed up for monthly updates
function fetchUsersForMonthlyUpdates() {
    global $conn;

    // Initialize an empty array to store user data
    $users = array();

    // SQL query to get users signed up for monthly updates
    $sql_users = "SELECT * FROM users WHERE updates_enabled = 1";
    $result_users = $conn->query($sql_users);

    // Check if there are any users
    if ($result_users->num_rows > 0) {
        // Fetch each user
        while ($row_users = $result_users->fetch_assoc()) {
            // Store user information
            $user = array(
                'id' => $row_users['id'],
                'email' => $row_users['email']
                // Add more user information as needed
            );

            // Push user data into the users array
            $users[] = $user;
        }
    }

    return $users;
}

// API endpoint to get users signed up for monthly updates and books
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'getUsersAndBooksForMonthlyUpdates') {
    try {
        // Fetch users signed up for monthly updates
        $users = fetchUsersForMonthlyUpdates();

        // Fetch books from the Google Books API
        $books = fetchBooksFromGoogleBooksAPI();

        // Return JSON response with both users and books
        header('Content-Type: application/json');
        echo json_encode(array('users' => $users, 'books' => $books));
    } catch (Exception $e) {
        // Return error response
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(array('error' => $e->getMessage()));
    }
}
?>

