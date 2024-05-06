<?php

require_once("createLog.php");


function doRegister($username, $password, $email)
{
	/* log data */ $log_path = "backend/services/authentication-receiver/functions/register.php";
	/* log */ createLog("Info", "Requesting database connection", $log_path);	
	
    $conn = getDatabaseConnection();
    if (!$conn) {
    	/* log */ createLog("Error", "Error connecting to the database", $log_path);
        return array("returnCode" => 500, "message" => "Error connecting to the database");
    }

    try {
        // Prepare SQL statement to prevent SQL injection
        /* log */ createLog("Info", "Registering user where username=".$username.", password=".$password." nad email=".$email, $log_path);
        $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $password, $email);
        $stmt->execute();

		/* log */ createLog("Info", "Successfully registered ".$username, $log_path);
        return array(
            "returnCode" => 200,
            "message" => "Registration successful"
        );
    } catch (mysqli_sql_exception $e) {
        // Log error or handle as needed
        /* log */ createLog("Error", "Error registering user ".$username.": ".$e->getMessage(), $log_path);
        return array("returnCode" => 400, "message" => "Error registering user: " . $e->getMessage());
    }
}

?>
