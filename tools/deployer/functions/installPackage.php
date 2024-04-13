<?php

function installPackage($environment, $packageFilePath, $projectDirectory, $installationFlags){
    // Get the parent directory of the project directory
    $parentDirectory = dirname($projectDirectory);

    // Delete the project directory if it exists
    if (file_exists($projectDirectory)) {
        deleteDirectory($projectDirectory);
    }

    // Move the package file to the parent directory
    $packageFileName = basename($packageFilePath);
    $newPackageFilePath = $parentDirectory . '/' . $packageFileName;

    if (!rename($packageFilePath, $newPackageFilePath)) {
        return array("returnCode" => '400', 'message' => "Failed to move package file to parent directory.");
    }

    // Unzip packageFilePath to $projectDirectory
    $command = "unzip -o $newPackageFilePath -d $projectDirectory";
    exec($command, $output, $returnCode);

    if ($returnCode !== 0) {
        return array("returnCode" => '400', 'message' => "Failed to unzip package.");
    }

    // Run installer with environment and installation flags
    $command = "cd $projectDirectory && ./installer.sh $environment $installationFlags";
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
        if (is_dir($path)) {
            deleteDirectory($path);
        } else {
            unlink($path);
        }
    }
    rmdir($dir);
}

?>
