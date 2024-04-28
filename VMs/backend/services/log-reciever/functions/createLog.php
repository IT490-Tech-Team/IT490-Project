<?php

function createLog($message, $message_type, $source)
{

var_dump($message, $message_type, $source);

	// File path
	$file_path = "./logs.txt";
	
	//file contents
	$file_desc = "This is a cross-system log file that contans all relevant events that occur across all connected systems.".PHP_EOL."Log types are listed with most critical first: Fatal, Error, Info, Debug".PHP_EOL.PHP_EOL;
	$file_headers = str_pad("Date", 25).str_pad("Message_Type", 15).str_pad("Message", 100).str_pad("Source", 50).PHP_EOL;

    try {
		// Open the file in append mode
		$file = fopen($file_path, "a");
		
		var_dump(filesize($file_path));
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
			$padded_date = str_pad($date, 25);
			$padded_type = str_pad($message_type, 15);
			$padded_message = str_pad($message, 100);
			$padded_source = str_pad($source, 50);

			// Append the padded data with column names to the file
			fwrite($file, $padded_date.$padded_type.$padded_message.$source . PHP_EOL);

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

