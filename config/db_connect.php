<?php
  $server = "us-cdbr-iron-east-02.cleardb.net";
  $user = "b5d2e358447bf0";
  $password = "afcbb231";
  $db = "heroku_503d5f6fd7b054e";

  // $server = "localhost";
  // $user = "ytuser";
  // $password = "test1234";
  // $db = "yt_classic";

  $conn = mysqli_connect($server, $user, $password, $db);
  if (!$conn) {
    echo 'Connection Error: ' . mysqli_error($conn);
  }
?> 