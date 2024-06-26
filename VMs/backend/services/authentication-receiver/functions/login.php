<?php

require_once("createLog.php");

function doLogin($username, $password)
{
	/* log data */ $log_path = "backend/services/authentication-receiver/functions/login.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);
	
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        /* log */
        createLog("Info", "Authenticating user where username=".$username." and password=".$password, $log_path);
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (!$row) {
        	/* log */ createLog("Alert", "Invalid authenticating where username=".$username." and password=".$password, $log_path);
            return array("returnCode" => 400, "message" => "Invalid username or password");
        }

        // Verify the password
        $hashed_password = $row['password'];
        if (!password_verify($password, $hashed_password)) {
            return array("returnCode" => 400, "message" => "Invalid username or password");
        }

        $userId = $row['id'];

        // Delete existing sessions for the user
        /* log */ createLog("Info", "Delete existing sessions for ".$username, $log_path);
        $stmt = $conn->prepare("DELETE FROM sessions WHERE userId = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        // Create a new session for the user
        /* log */ createLog("Info", "Create a new session for ".$username, $log_path);
        $sessionId = uniqid();
        $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
        $stmt = $conn->prepare("INSERT INTO sessions (sessionId, userId, expired_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $sessionId, $userId, $expiryDate);
        $stmt->execute();

		/* log */ createLog("Info", "Login successful by ".$username, $log_path);
        return array(
            "returnCode" => 200,
            "sessionId" => $sessionId,
            "expired_at" => $expiryDate,
            "message" => "Login successful"
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error retrieving user ".$username." with password=".$password.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}
?>
