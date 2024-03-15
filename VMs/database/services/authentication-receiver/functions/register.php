<?php

function doRegister($username, $password)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $password);
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
