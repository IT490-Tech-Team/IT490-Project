<?php

function getUser($sessionId)
{
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT userId FROM sessions WHERE sessionId = ?");
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (!$row) {
            return array("returnCode" => 400, "message" => "Invalid session ID");
        }

        $userId = $row['userId'];

        // Now fetch user details from the users table
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $userDetails = $result->fetch_assoc();
        if (!$userDetails) {
            return array("returnCode" => 400, "message" => "User not found");
        }

        // Fetch user library entries
        $stmt = $conn->prepare("SELECT * FROM user_library WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $userLibraries = array();
        while ($libraryRow = $result->fetch_assoc()) {
            $userLibraries[] = $libraryRow;
        }

        // User details and library entries retrieved successfully
        return array(
            "returnCode" => 200,
            "message" => "User details and libraries retrieved successfully",
            "userDetails" => $userDetails,
            "userLibraries" => $userLibraries
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}

?>