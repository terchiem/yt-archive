<?php
  $production = true;

  if ($production) {
    define('API_KEY', getenv('API_KEY'));
    define('DB_SERVER', "us-cdbr-iron-east-02.cleardb.net");
    define('DB_USER', "b5d2e358447bf0");
    define('DB_PASSWORD', "afcbb231");
    define('DB_NAME', "heroku_503d5f6fd7b054e");
  } else {
    include 'secret.php';
    define('DB_SERVER', "localhost");
    define('DB_USER', "ytuser");
    define('DB_PASSWORD', "test1234");
    define('DB_NAME', "yt_classic2");
  }

  $conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_NAME);
  if (!$conn) {
    echo 'Connection Error: ' . mysqli_error($conn);
  }
?> 