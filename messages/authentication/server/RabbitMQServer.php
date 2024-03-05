#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doRegister($username,$password){
	//error handling for connecting to mysql database
	try {	
		//will probably have to change this, depending on database location
		$conn = new mysqli('localhost', 'tester', '12345', 'userdb'); 
	} catch (Exception $e) {
		$response = "Error";
		echo json_encode($response);
		exit(0);
	}
	
	//check if username already exists
	$check_sql = "SELECT COUNT(*) as count FROM users WHERE username = '$username'";
   	$result = $conn->query($check_sql);
    	$row = $result->fetch_assoc();
    	if ($row['count'] > 0) {
        	// username already exists, return false
        	return false;
    	}
	
	$sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
	if ($conn->query($sql) === TRUE) { 
		return true; 
	} else { 
		return false; 
	}               
}

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
	try {   
                //will probably have to change this, depending on database location
                $conn = new mysqli('localhost', 'tester', '12345', 'userdb'); 
        } catch (Exception $e) {
                $response = "Error";
                echo json_encode($response);
                exit(0);
        }
  	
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);

        // Check if a row was returned (i.e., user exists and password is correct)
        if ($result->num_rows > 0) {
           // $response = "Login successful";
       	   return true;
	 } else {
            $response = "Invalid username or password";
           return false; 
	} 
    //return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "register":
      return doRegister($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("RabbitMQ.ini","server");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

