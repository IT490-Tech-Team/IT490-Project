<?php
 
// Check if the request method is POST

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if username and password are provided
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        // Sanitize input to prevent SQL injection
	try {	
		$conn = new mysqli('localhost', 'tester', '12345', 'userdb'); 
		
	} catch (Exception $e) {
		$response = "Error";
		echo json_encode($response);
		exit(0);
	}
 
		$username = $_POST["username"];
		$password = $_POST["password"];
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        // Check if a row was returned (i.e., user exists and password is correct)
        if ($result->num_rows > 0) {
            $response = "Login successful";
        } else {
            $response = "Invalid username or password";
        } 
	 	
} 
}
 
 	echo json_encode($response);
	exit(0);
  
 /*    $username = $_POST["username"];
        $password = $_POST["password"];

        // Database configuration
        $servername = "localhost";
        $username_db = "test";
        $password_db = "testpassword";
        $database = "userdb";

        // Create connection
        $conn = new mysqli($servername, $username_db, $password_db, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Print that mysqli is connected to the database
        echo "Connected to MySQL database successfully<br>";

        // Query the database to check if the user exists and the password is correct
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        // Check if a row was returned (i.e., user exists and password is correct)
        if ($result->num_rows > 0) {
            echo "Login successful";
        } else {
            echo "Invalid username or password";
        }

        // Close connection
        $conn->close();
    } else {
        echo "Username and password are required";
    }
} else {
    echo "Invalid request method";
}*/
?>


/*

<?php

// MySQL server configuration
$servername = "127.0.0.1"; // Assuming MySQL server is running on localhost
$username = "test"; // Your MySQL username
$password = "testpassword"; // Your MySQL password
$database = "userdb"; // Your MySQL database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
echo $conn;
// Check connection
if ($conn->connect_error) {
    // If connection fails, print "unsuccessful"
    $response = "unsuccessful";
} else {
    // If connection is successful, print "successful"
    $response = "successful";
}

// Close connection
//$conn->close();

// Send response back to the client
echo json_encode($response);
exit(0);
?>

/*
<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$database = "userdb"; // Assuming your database name is user-database

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "connected";
?>
/*
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the 'type' parameter is set and equals 'login'
    if (isset($_POST["type"]) && $_POST["type"] == "login") {
        // Check if username and password are provided
        if (isset($_POST["uname"]) && isset($_POST["pword"])) {
            // Sanitize input to prevent SQL injection
            $username = $conn->real_escape_string($_POST["uname"]);
            $password = $conn->real_escape_string($_POST["pword"]);

            // Query the database to check if the user exists
            $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $result = $conn->query($sql);

            // Check if a row was returned (i.e., user exists)
            if ($result->num_rows > 0) {
                $response = "Login successful"; // You can customize the response message here
            } else {
                $response = "Invalid username or password"; // You can customize the response message here
            }
        } else {
            $response = "Username and password are required"; // You can customize the response message here
        }
    } else {
        $response = "Unsupported request type"; // You can customize the response message here
    }
} else {
    $response = "Invalid request"; // You can customize the response message here
}

// Close connection
$conn->close();

// Send response back to the client
echo json_encode($response);
?>
*/
