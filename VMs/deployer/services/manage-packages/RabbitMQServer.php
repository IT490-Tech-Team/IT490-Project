#!/usr/bin/php
<?php
// Include required files
require_once ('rabbitMQLib.inc');
include_once ("functions/downloadPackage.php");
include_once ("functions/addPackage.php");
include_once ("functions/sendMessage.php");
include_once ("functions/sendPackageInfo.php");
include_once ("functions/sendPackageInfoVersion.php");
include_once ("functions/managePackage.php");

function getDatabaseConnection()
{
  $host = 'localhost';
  $username = 'bookQuest';
  $password = '3394dzwHi0HJimrA13JO';
  $database = 'bookShelf';

  // Create connection
  $conn = new mysqli($host, $username, $password, $database);

  // Check connection
  if ($conn->connect_error) {
    // Log error or handle as needed
    return null;
  }

  return $conn;
}

// Function to process incoming requests
function requestProcessor($request)
{

  $computers = array(
    "dev" => array(
        "hostname" => "dev-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    ),
    "test" => array(
        "hostname" => "test-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    ),
    "prod" => array(
        "hostname" => "prod-backend",
        "username" => "ubuntu",
        "password" => "ubuntu"
    )
  );

  // Debug: Display received request
    echo "received request" . PHP_EOL;
    var_dump($request);

    // Check if request type is set
    if (!isset($request['type'])) {
        return "ERROR: unsupported message type";
    }

    // Check if request type is 'create-package'
    if ($request["type"] === "create-package") {
        // Check if 'environment' is set in the request
        if (!isset($request['environment'])) {
            return array("returnCode" => '400', 'message' => "ERROR: 'environment' not provided in request");
        }

        // Retrieve environment details from the $computers array
        $environment = $request['environment'];
        if (!array_key_exists($environment, $computers)) {
          return array("returnCode" => '400', 'message' => "ERROR: Invalid environment provided");
        }
        
        if ($environment !== "dev"){
          return array("returnCode" => '400', 'message' => "Only dev can create packages.");
        }
        // Extract hostname, username, and password for the given environment
        $hostname = $computers[$environment]['hostname'];
        $username = $computers[$environment]['username'];
        $password = $computers[$environment]['password'];

        // Check if 'file_location' is set in the request
        if (!isset($request['file_location'])) {
            return "ERROR: 'file_location' not provided in request";
        }

        // Call createPackage function with retrieved details
        $file = downloadPackage($hostname, $username, $password, $request['file_location']);

        // Check if the download was successful
        if ($file["returnCode"] !== '200') {
          return array("returnCode" => '400', 'message' => "Error: Failed to download the package.");
        }

        // Extracting additional information from $request
        $name = isset($request['name']) ? $request['name'] : ''; // Default to empty string if 'name' is not set
        $fileLocation = $file['message']; // File location obtained from downloadPackage function
        $installationFlags = isset($request['install_arguments']) ? $request['install_arguments'] : ''; // Default to empty string if 'install_arguments' is not set

        $addResult = addPackage($name, $fileLocation, $installationFlags, $environment);

        // Check the result of adding package information
        if ($addResult["returnCode"] !== '200') {
          return array("returnCode" => '400', 'message' => "Error: Failed to add package information to the database.");
        }

        if ($environment === "dev"){
          sendMessage("test", $name, $fileLocation, $installationFlags);
        }

        
        return array("returnCode" => '0', 'message' => "Request processed.");
    }
    if ($request["type"] === "package-info") {
      if (isset($request["version"]) && $request["version"] !== null) {
          return sendPackageInfoVersion($request["name"], $request["version"]);
      } else {
          return sendPackageInfo($request["name"]);
      }
    }
    if($request["type"] === "manage-package"){
      $managedPackageResponse = managePackage($request["packageName"], $request["packageVersion"], $request["action"], $request["environment"]);

      if($managedPackageResponse["returnCode"] === "201"){
        if($request["environment"] === "test"){
          sendMessage("prod", $managedPackageResponse["package_name"], $managedPackageResponse["file_location"], $managedPackageResponse["installation_flags"]);
        }
      }
      else if ($managedPackageResponse["returnCode"] === "202"){
        $packageResults = sendPackageInfo($request["packageName"]);

        sendMessage($request["environment"], $packageResults["package_name"], $packageResults["file_location"], $packageResults["installation_flags"]);
      }

      return $managedPackageResponse;
    }

    // Default return if request type is not recognized
    return array("returnCode" => '0', 'message' => "Request not processed.");
}

$connectionConfig = [
  "BROKER_HOST" => "localhost",
  "BROKER_PORT" => 5672,
  "USER" => "bookQuest",
  "PASSWORD" => "8bkJ3r4dWSU1lkL6HQT7",
  "VHOST" => "bookQuest",
];

$exchangeQueueConfig = [
  "EXCHANGE_TYPE" => "topic",
  "AUTO_DELETE" => true,
  "EXCHANGE" => "createPackageExchange",
  "QUEUE" => "createPackageQueue",
];

// Create RabbitMQ server instance
$server = new rabbitMQServer($connectionConfig, $exchangeQueueConfig);

// Main execution starts here
echo "testRabbitMQServer BEGIN" . PHP_EOL;
// Process incoming requests
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END" . PHP_EOL;
?>