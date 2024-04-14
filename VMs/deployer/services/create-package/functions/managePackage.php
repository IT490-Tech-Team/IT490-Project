<?php

function managePackage($packageName, $packageVersion, $action, $environment) {
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => '400', 'message' => "Error: Database connection failed.");
    }

    // Escape variables for security
    $packageName = mysqli_real_escape_string($conn, $packageName);
    $packageVersion = mysqli_real_escape_string($conn, $packageVersion);
    $environment = mysqli_real_escape_string($conn, $environment);

    if ($action === "accept") {
        // Update the stage of the package entry in the database using prepared statement
        $sql = "UPDATE packages SET stage = ? WHERE name = ? AND version = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            $stmt->close();
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("sss", $environment, $packageName, $packageVersion);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error executing statement: " . $stmt->error);
        }
        
        $stmt->close();
        
        // Fetch package details
        $sql_fetch = "SELECT name, file_location, installation_flags FROM packages WHERE name = ? AND version = ?";
        $stmt_fetch = $conn->prepare($sql_fetch);
        
        if (!$stmt_fetch) {
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error preparing statement: " . $conn->error);
        }
        
        $stmt_fetch->bind_param("ss", $packageName, $packageVersion);
        if (!$stmt_fetch->execute()) {
            $stmt_fetch->close();
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error executing statement: " . $stmt_fetch->error);
        }
        
        $result = $stmt_fetch->get_result();
        if ($result->num_rows === 0) {
            $stmt_fetch->close();
            $conn->close();
            return array("returnCode" => '404', 'message' => "No package found with the name: $packageName and version: $packageVersion");
        }
        
        $row = $result->fetch_assoc();
        $stmt_fetch->close();
        $conn->close();
        
        return array_merge(array("returnCode" => '201', 'message' => "Package information updated successfully."), array(
            "package_name" => $row['name'],
            "file_location" => $row['file_location'],
            "installation_flags" => $row['installation_flags']
        ));
        
    } elseif ($action === "deny") {
        // Delete the package entry from the database using prepared statement
        $sql = "DELETE FROM packages WHERE name = ? AND version = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            $stmt->close();
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error preparing statement: " . $conn->error);
        }
        
        $stmt->bind_param("ss", $packageName, $packageVersion);
        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            return array("returnCode" => '400', 'message' => "Error executing statement: " . $stmt->error);
        }
        
        $stmt->close();
        $conn->close();
        
        return array("returnCode" => '202', 'message' => "Package information deleted from the database successfully.");
        
    } else {
        $conn->close();
        return array("returnCode" => '400', 'message' => "Invalid action: $action");
    }
}

?>
