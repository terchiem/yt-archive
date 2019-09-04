<?php
  function createSearchQuery($conn, $q) {
    $item = mysqli_real_escape_string($conn, $q);
    return "SELECT * FROM videos WHERE title LIKE '%$item%'";
  }




?>