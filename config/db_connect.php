<?php
  $server = "localhost";
  $user = "ytuser";
  $password = "test1234";
  $db = "yt_classic";

  // $conn = new mysqli($server, $user, $password, $db);
  $conn = mysqli_connect($server, $user, $password, $db);
  if (!$conn) {
    echo 'Connection Error: ' . mysqli_error($conn);
  }
?> 