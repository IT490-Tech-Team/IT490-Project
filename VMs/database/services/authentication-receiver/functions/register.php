<?php

function doRegister($username, $password)
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

  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

  try {
    $result = $conn->query($sql);
  } catch (Exception $e) {

    $response = array(
      "returnCode" => '400',
      "message" => "Error registering user: " . $e->getMessage() // Append exception message to the error message
    );
    return $response;
  }

  if ($result === TRUE) {
    $response = array(
      "returnCode" => '200',
      "message" => "Registration successful"
    );
    return $response;
  } else {
    $response = array(
      "returnCode" => '400',
      "message" => "Error registering user"
    );
    return $response;
  }
}

?>