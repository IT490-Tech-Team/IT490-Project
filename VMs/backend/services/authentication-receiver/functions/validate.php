<?php

require_once("createLog.php");


function doValidate($sessionId)
{
	/* log data */ $log_path = "backend/services/authentication-receiver/functions/validate.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);	
	
    $conn = getDatabaseConnection();
    if (!$conn) {
	    /* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        /* log */ createLog("Info", "Validating sessionId=".$sessionId, $log_path);
        $stmt = $conn->prepare("SELECT * FROM sessions WHERE sessionId = ?");
        $stmt->bind_param("s", $sessionId);
        $stmt->execute();
        $result = $stmt->get_result();

        $row = $result->fetch_assoc();
        if (!$row) {
            return array("returnCode" => 400, "message" => "Invalid session ID");
        }

        // Check if the session is expired
        $expiryDate = strtotime($row['expired_at']);
        $currentDate = strtotime(date('Y-m-d H:i:s'));

        if ($expiryDate < $currentDate) {
        	/* log */ createLog("Alert", "Session expired where sessionId=".$sessionId, $log_path);
            return array("returnCode" => 400, "message" => "Session expired");
        }

        // Session is valid
        /* log */ createLog("Info", "Session validated successfully where sessionId=".$sessionId, $log_path);
        return array(
            "returnCode" => 200,
            "message" => "Session validated successfully"
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error retrieving valid session where sessionId=".$sessionId.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 500, "message" => "Error executing query: " . $e->getMessage());
    }
}

?>
