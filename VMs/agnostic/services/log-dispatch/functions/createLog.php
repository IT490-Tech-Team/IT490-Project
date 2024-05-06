<?php

function createLog($message_type, $message, $source)
{
    // ignore message types (Error, Info, Debug)
    $ignored_types = [];
    if (in_array($message_type, $ignored_types)) {
        return array("returnCode" => 200, "message" => "Ignoring log types: " . implode($ignored_types));
    }
    
    // File path
    $file_path = "./logs.txt";

    // File description and headers
    $file_desc = "This is a cross-system log file that contains all relevant events that occur across all connected systems." . PHP_EOL . "Log types: Error, Alert, Info, Debug" . PHP_EOL . "Ignoring log types: [" . implode(", ", $ignored_types) . "]" . PHP_EOL . PHP_EOL;
    $file_headers = str_pad("Date", 23) . str_pad("Type", 8) . str_pad("Message", 70) . "Source" . PHP_EOL;

    // Current date and time
    $date = date("Y-m-d H:i:s");

    // Pad the data for each column
    $padded_date = str_pad($date, 23);
    $padded_type = str_pad($message_type, 8);
    $padded_message = str_pad($message, 70);
    $padded_source = str_pad($source, 100);

    // Construct the log entry with the padded data
    $log_entry = $padded_date . $padded_type. $padded_message . $padded_source . PHP_EOL;
    
    // check if file is empty
    if (filesize($file_path) == 0) {
    	//$log_entry = $file_desc . $file_headers . $log_entry;
    }
    var_dump(filesize($file_path));
        
    // Open and append to file
    try {
    
        // Open the file in append mode
        $file = fopen($file_path, "a");
        if ($file === false) {
            var_dump("Failed to open file.");
            return array("returnCode" => 0, "message" => "Error opening or creating the log file");
        }

        // Write the log entry to the file
        fwrite($file, $log_entry);

        // Close the file
        fclose($file);

        // Return success message
        return array("returnCode" => 200, "message" => "Log filed successfully");
    } catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error filing log " . $e->getMessage());
    }
}

?>

