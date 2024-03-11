<?php

function getDatabaseConnection()
{
    $host = 'localhost';
    $username = 'bookQuest';
    $password = '3394dzwHi0HJimrA13JO';
    $database = 'userdb';

    try {
        $conn = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        // Set PDO to throw exceptions on error
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // Log error or handle as needed
        return null;
    }
}

function doLogin($username, $password)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
        $stmt->execute(array(':username' => $username, ':password' => $password));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return array("returnCode" => 400, "message" => "Invalid username or password");
        }

        $userId = $row['id'];

        // Delete existing sessions for the user
        $stmt = $conn->prepare("DELETE FROM sessions WHERE userId = :userId");
        $stmt->execute(array(':userId' => $userId));

        // Create a new session for the user
        $sessionId = uniqid();
        $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
        $stmt = $conn->prepare("INSERT INTO sessions (sessionId, userId, expired_at) VALUES (:sessionId, :userId, :expiryDate)");
        $stmt->execute(array(':sessionId' => $sessionId, ':userId' => $userId, ':expiryDate' => $expiryDate));

        return array(
            "returnCode" => 200,
            "sessionId" => $sessionId,
            "expired_at" => $expiryDate,
            "message" => "Login successful"
        );
    } catch (PDOException $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}
?>
