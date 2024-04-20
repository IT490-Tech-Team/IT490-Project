<?php

function doRegister($username, $password, $email)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashed_password, $email);
        $stmt->execute();

        return array(
            "returnCode" => 200,
            "message" => "Registration successful"
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 400, "message" => "Error registering user: " . $e->getMessage());
    }
}

?>
