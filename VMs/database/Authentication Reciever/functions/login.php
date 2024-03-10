<?php

function doLogin($username, $password)
{
  try {
    $conn = new mysqli('localhost', 'guest', '3394dzwHi0HJimrA13JO', 'userdb');
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error connecting to the database"
    );
    return $response;
  }

  $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";

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
      "message" => "Invalid username or password"
    );
    return $response;
  }

  // Fetch the user details
  $row = $result->fetch_assoc();
  $userId = $row['id'];

  $sql = "DELETE FROM sessions WHERE userId = '$userId'";
  try {
    $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  // Create a new session for the user
  $sessionId = uniqid();
  $expiryDate = date('Y-m-d H:i:s', strtotime('+7 days'));
  $sql = "INSERT INTO sessions (sessionId, userId, expired_at) VALUES ('$sessionId', '$userId', '$expiryDate')";
  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {
    $response = array(
      "returnCode" => '500',
      "message" => "Error executing query: " . $e->getMessage()
    );
    return $response;
  }

  if ($result === TRUE) {
    // Return the session ID to the user
    return array(
      "returnCode" => '200',
      "sessionId" => $sessionId,
      "expired_at" => $expiryDate,
      "message" => "Login successful"
    );
  } else {
    // Return an error message if the session ID couldn't be inserted
    return array(
      "returnCode" => '500',
      "message" => "Error creating session"
    );
  }
  return true;
}

?>