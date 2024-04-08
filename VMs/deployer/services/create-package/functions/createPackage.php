<?php

function createPackage($remoteHostname, $remoteUsername, $remotePassword, $fileAbsolutePath) {
    // Validate inputs
    if (!file_exists($fileAbsolutePath)) {
        return "Error: File does not exist at the provided path.";
    }

    // Check file extension
    $extension = pathinfo($fileAbsolutePath, PATHINFO_EXTENSION);
    if ($extension !== 'tar.gz') {
        return "Error: File extension is not '.tar.gz'.";
    }

    // Establish SSH connection
    $connection = ssh2_connect($remoteHostname);
    if (!$connection) {
        return "Error: Unable to establish SSH connection to $remoteHostname.";
    }

    // Authenticate with SSH credentials
    if (!ssh2_auth_password($connection, $remoteUsername, $remotePassword)) {
        return "Error: Authentication failed for $remoteUsername.";
    }

    // Destination directory on the remote server
    $destinationDirectory = '/opt/packages/';

    // Get the file name from the absolute path
    $filename = basename($fileAbsolutePath);

    // Copy the file to the remote server
    if (!ssh2_scp_send($connection, $fileAbsolutePath, $destinationDirectory.'/'.$filename)) {
        return "Error: Failed to copy the file to the remote server.";
    }

    // Close SSH connection
    ssh2_disconnect($connection);

    return "File copied successfully to $remoteHostname.";
}

// Usage example:
$fileAbsolutePath = "/path/to/your/file.tar.gz";
$remoteUsername = "username";
$remotePassword = "password";
$remoteHostname = "example.com";

$result = createPackage($fileAbsolutePath, $remoteUsername, $remotePassword, $remoteHostname);
echo $result;
?>
