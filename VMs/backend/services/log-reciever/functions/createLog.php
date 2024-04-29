<?php

function createLog($message_type, $message, $source)
{
	// ignore message types (Error, Info, Debug)
	$ignored_types = [];
	if (in_array($message_type, $ignored_types)) {
		return array("returnCode" => 200, "message" => "Ignoring log types: " . implode($ignored_types));
	}
	
	//file description
	$file_desc = "This is a cross-system log file that contans all relevant events that occur across all connected systems.".PHP_EOL."Log types are listed with most critical first: Error, Info, Debug".PHP_EOL."Ignoring log types: [".implode(", ", $ignored_types)."]".PHP_EOL.PHP_EOL;
	$file_headers = str_pad("Date", 23).str_pad("Type", 8).str_pad("Source", 50)."Message".PHP_EOL;

	// File path
	$file_path = "./logs.txt";

    try {
		// Open the file in append mode
		$file = fopen($file_path, "a");
		// file file is empty
		if (filesize($file_path) == 0) {
			// Add description and column names to the file
			fwrite($file, $file_desc);
			fwrite($file, $file_headers);
		}
		
		// Check if file opened successfully
		if ($file) {
		
			// Current date and time
			$date = date("Y-m-d H:i:s");
			
			// Pad the data for each column
			$padded_date = str_pad($date, 23);
			$padded_type = str_pad($message_type, 8);
			$padded_message = str_pad($message, 1000);
			$padded_source = str_pad($source, 50);

			// Append the padded data with column names to the file
			fwrite($file, $padded_date.$padded_type.$padded_source.$message . PHP_EOL);

			// Close
			fclose($file);
		} 
		
		// could not open file
		else {
			return array("returnCode" => 0, "message" => "Error opening or creating the log file");
		}
	
		// Return success message
		return array("returnCode" => 200, "message" => "Log filed successfully");
    } 
    
    catch (Exception $e) {
        // Log error or handle as needed
        return array("returnCode" => 500, "message" => "Error filing log " . $e->getMessage());
    }
}

?>

