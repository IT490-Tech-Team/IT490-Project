<?php

function downloadPackage($remoteHostname, $remoteUsername, $remotePassword, $remoteFilePath) {
    // Local directory to save the file
    $localDirectory = "/" . implode("/", array_slice(explode("/", $_SERVER["PWD"]),1, -4)) . "/packages/backups";

    // Establish SSH connection
    $connection = ssh2_connect($remoteHostname);
    if (!$connection) {
        return array("returnCode" => '400', 'message' => "Error: Unable to establish SSH connection to $remoteHostname.");
    }

    // Authenticate with SSH credentials
    if (!ssh2_auth_password($connection, $remoteUsername, $remotePassword)) {
        return array("returnCode" => '400', 'message' => "Error: Authentication failed for $remoteUsername.");
    }

    // Remote file path
    $remoteFile = $remoteFilePath;
    // Local file path
    $localFile = $localDirectory . '/' . basename($remoteFile);

    // Copy the file from the remote server to the local machine
    if (!ssh2_scp_recv($connection, $remoteFile, $localFile)) {
        return array("returnCode" => '400', 'message' => "Error: Failed to copy the file from the remote server.");
    }

    // Close SSH connection
    ssh2_disconnect($connection);

    return $localFile;
}
?>