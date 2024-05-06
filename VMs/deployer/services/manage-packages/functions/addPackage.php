<?php

function addPackage($name, $fileLocation, $installationFlags, $stage) {
    // Connect to the database
    $conn = getDatabaseConnection();
    if (!$conn) {
        return array("returnCode" => '400', 'message' => "Error: Database connection failed.");
    }

    // Escape variables for security
    $name = mysqli_real_escape_string($conn, $name);
    $fileLocation = mysqli_real_escape_string($conn, $fileLocation);
    $installationFlags = mysqli_real_escape_string($conn, $installationFlags);
    $stage = mysqli_real_escape_string($conn, $stage);

    // Check if a package with the same name exists
    $sql_check = "SELECT MAX(version) AS max_version FROM packages WHERE name = '$name'";
    $result_check = $conn->query($sql_check);
    $row = $result_check->fetch_assoc();
    $previousVersion = $row['max_version'] ? $row['max_version'] : 0;
    $version = $previousVersion + 1;

    // SQL query to insert package information
    $sql = "INSERT INTO packages (name, version, file_location, installation_flags, stage) 
            VALUES ('$name', '$version', '$fileLocation', '$installationFlags', '$stage')";

    // Execute SQL query
    if ($conn->query($sql) === TRUE) {
        $message = "Package information inserted into database successfully.";
        $returnCode = '200';
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
        $returnCode = '400';
    }

    // Close database connection
    $conn->close();

    return array("returnCode" => $returnCode, 'message' => $message);
}

?>
