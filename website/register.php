<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if username and password are provided
    if (isset($_POST["username"]) && isset($_POST["password"])) {
    
	  // Validate the form data (you may want to perform more thorough validation)
    $username = $_POST['username'];
    $password = $_POST['password'];

	
	// Check if all required fields are provided
    if (empty($username) || empty($password)) {
        echo json_encode("All fields are required.");
       exit(0);
    }


	try {   
                $conn = new mysqli('localhost', 'tester', '12345', 'userdb'); 

        } catch (Exception $e) {
                $response = "Error";
                echo json_encode($response);
                exit(0);
        }
	try {
		$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
		$conn->query($sql);
	} catch (Exception $e) { 
		$response = "Error";
		echo json_encode($response);
		exit(0);
	} 
	$response = "Registration successful.";
            
	echo json_encode("Connected to sql.");
	
/*

    // Prepare and execute the SQL statement to insert the new user into the database
    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $hashedPassword);
    if ($stmt->execute()) {
       $response = "Registration successful.";
    	echo json_encode($response);
	} else {
	$response = "Error connecting";
        echo json_encode($response);
    }

    // Close the connection
    $stmt->close();
    $conn->close();
} else {
    // If the form was not submitted or the type is not 'register', return an error
    echo json_encode("Invalid request.");*/
}
}

?>
