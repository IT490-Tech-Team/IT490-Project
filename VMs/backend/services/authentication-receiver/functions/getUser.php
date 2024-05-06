<?php

require_once("createLog.php");

function getUser($sessionId)
{
	/* log data */ $log_path = "backend/services/authentication-receiver/functions/getUser.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);	
	
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
    	/* log */ createLog("Info", "Validating sessionId=".$sessionId, $log_path);
        $stmt = $conn->prepare("SELECT userId FROM sessions WHERE sessionId = ?");
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (!$row) {
        	/* log */ createLog("Alert", "Invalid session ID ", $log_path);
            return array("returnCode" => 400, "message" => "Invalid session ID");
        }

        $userId = $row['userId'];

   
        // Now fetch user details from the users table
        /* log */ createLog("Info", "Fetching details of user where sessionId=".$sessionId, $log_path);
        
        $stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = ?");        
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $userDetails = $result->fetch_assoc();
        if (!$userDetails) {
        	createLog("Alert", "User where userId=".$userId." not found", $log_path);
            return array("returnCode" => 400, "message" => "User not found");
        }
	
        // Fetch user library entries
        /* log */ createLog("Info", "Fetching library of user where sessionId=".$sessionId, $log_path);
        
        $stmt = $conn->prepare("SELECT * FROM user_library WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $userLibraries = array();
        while ($libraryRow = $result->fetch_assoc()) {
            $userLibraries[] = $libraryRow;
        }

        // User details and library entries retrieved successfully
        /* log */ createLog("Info", "User details and library where sessionId=".$sessionId." retrieved successfully", $log_path);
        return array(
            "returnCode" => 200,
            "message" => "User details and libraries retrieved successfully",
            "userDetails" => $userDetails,
            "userLibrary" => $userLibraries
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Info", "Error retrieving users where sessionId=".$sessionId.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}

?>
