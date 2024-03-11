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

function doValidate($sessionId)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM sessions WHERE sessionId = :sessionId");
        $stmt->execute(array(':sessionId' => $sessionId));

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return array("returnCode" => 400, "message" => "Invalid session ID");
        }

        // Check if the session is expired
        $expiryDate = strtotime($row['expired_at']);
        $currentDate = strtotime(date('Y-m-d H:i:s'));

        if ($expiryDate < $currentDate) {
            return array("returnCode" => 400, "message" => "Session expired");
        }

        // Session is valid
        return array(
            "returnCode" => 200,
            "message" => "Session validated successfully"
        );
    } catch (PDOException $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}

?>
