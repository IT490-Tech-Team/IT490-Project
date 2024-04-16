#!/usr/bin/php
<?php

// Include required files
require_once __DIR__ . "/../rabbitMQLib.inc";

// Function to send package information
function sendPackageInfoVersion($packageName, $packageVersion)
{
    // Connect to the database
    $conn = getDatabaseConnection();

    if (!$conn) {
        echo "Failed to connect to the database.\n";
        return array("returnCode" => 500, "message" => "Failed to connect to the database.");
    }

	// Prepare SQL query to get the latest package with matching name and version
	$sql = "SELECT * FROM packages WHERE name = ? AND version = ? AND status = 'good' ORDER BY id DESC LIMIT 1";

	$stmt = $conn->prepare($sql);
	if (!$stmt) {
		echo "Error preparing statement: " . $conn->error . "\n";
		return array("returnCode" => 500, "message" => "Error preparing statement: " . $conn->error);
	}

	// Bind parameters
	$stmt->bind_param("ss", $packageName, $packageVersion);

	// Execute the query
	if (!$stmt->execute()) {
		echo "Error executing query: " . $stmt->error . "\n";
		return array("returnCode" => 500, "message" => "Error executing query: " . $stmt->error);
	}

    // Get result
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "No package found with the name: $packageName\n";
        return array("returnCode" => 404, "message" => "No package found with the name: $packageName");
    }

    // Fetch the row
    $row = $result->fetch_assoc();

    // Close the database connection
    $stmt->close();
    $conn->close();

    // Return the package information
    return array(
        "returnCode" => 200,
        "package_name" => $row['name'],
        "version" => $row['version'],
        "stage" => $row['stage'],
        "installation_flags" => $row['installation_flags'],
        "file_location" => $row['file_location']
    );
}

?>
