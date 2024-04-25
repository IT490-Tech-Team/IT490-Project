<?php

function doLogin($username, $password)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (!$row) {
            return array("returnCode" => 400, "message" => "Invalid username or password");
        }

        // Verify the password
        $hashed_password = $row['password'];
        if (!password_verify($password, $hashed_password)) {
            return array("returnCode" => 400, "message" => "Invalid username or password");
        }

        $userId = $row['id'];

        // Delete existing sessions for the user
        $stmt = $conn->prepare("DELETE FROM sessions WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Create a new session for the user
        $sessionId = uniqid();
        $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
        $stmt = $conn->prepare("INSERT INTO sessions (sessionId, userId, expired_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $sessionId, $userId, $expiryDate);
        $stmt->execute();

        return array(
            "returnCode" => 200,
            "sessionId" => $sessionId,
            "expired_at" => $expiryDate,
            "message" => "Login successful"
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}
?>
