<?php

function doValidate($sessionId)
{
  try {
    $conn = new mysqli('localhost', 'bookQuest', '3394dzwHi0HJimrA13JO', 'userdb');
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error connecting to the database"
    );
    return $response;
  }

  // Validate session ID
  $sql = "SELECT * FROM sessions WHERE sessionId = '$sessionId'";

  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  if ($result->num_rows <= 0) {
    $response = array(
      "returnCode" => '400',
      "message" => "Invalid session ID"
    );
    return $response;
  }

  // Check if the session is expired
  $row = $result->fetch_assoc();
  $expiryDate = strtotime($row['expired_at']);
  $currentDate = strtotime(date('Y-m-d H:i:s'));

  if ($expiryDate < $currentDate) {
    $response = array(
      "returnCode" => '400',
      "message" => "Session expired"
    );
    return $response;
  }

  // Session is valid
  return array(
    "returnCode" => '200',
    "message" => "Session validated successfully"
  );
}

?>