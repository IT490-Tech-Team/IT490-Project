<?php

function installPackage($environment, $packageFilePath, $projectDirectory, $installationFlags){
    // Get the parent directory of the project directory
    $parentDirectory = dirname($projectDirectory);

    echo "Parent directory: $parentDirectory\n";

    // Move the package file to the parent directory
    $packageFileName = basename($packageFilePath);
    $newPackageFilePath = $parentDirectory . '/' . $packageFileName;

    echo "Moving package file to: $newPackageFilePath\n";

    if (!rename($packageFilePath, $newPackageFilePath)) {
        return array("returnCode" => '400', 'message' => "Failed to move package file to parent directory.");
    }

    // Delete the project directory if it exists
    if (file_exists($projectDirectory)) {
        echo "Deleting project directory: $projectDirectory\n";
        deleteDirectory($projectDirectory);
    }

    // Unzip packageFilePath to $projectDirectory
    $command = "unzip -o $newPackageFilePath -d $parentDirectory";
    echo "Unzipping package file to project directory: $projectDirectory\n";
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        return array("returnCode" => '400', 'message' => "Failed to unzip package.");
    }


    // Run installer with environment and installation flags
    $command = "sudo /home/ubuntu/IT490-Project/installer.sh -backend $installationFlags";
    echo "Running installer script: $command\n";
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        return array("returnCode" => '400', 'message' => "Failed to run installer.");
    }

    return array("returnCode" => '200', 'message' => "Package installed successfully.");
}

// Helper function to recursively delete a directory
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $path = "$dir/$file";
        if ($file === '.git') {
            continue; // Skip deleting .git directory
        }
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

// Check if the script is run directly from the command line
if (basename(__FILE__) === basename($_SERVER['PHP_SELF'])) {
    // Check if the correct number of arguments are provided
    if ($argc < 5) {
        echo "Usage: $argv[0] <environment> <packageFilePath> <projectDirectory> <installationFlags>\n";
        exit(1);
    }

    // Extract command line arguments
    $environment = $argv[1];
    $packageFilePath = $argv[2];
    $projectDirectory = $argv[3];
    $installationFlags = $argv[4];

    // Call the installPackage function with command line arguments
    $result = installPackage($environment, $packageFilePath, $projectDirectory, $installationFlags);

    // Output the result
    echo $result['message'] . "\n";
    exit($result['returnCode']);
}

?>
