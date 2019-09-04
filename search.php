<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';

  $q = $_GET["q"];
  $sql = createSearchQuery($conn, $q);
  $result = mysqli_query($conn, $sql);

  // if less than 20 results
    // search api call to youtube
    // add searches to database

  $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <h3>Search results for '<?= htmlspecialchars($q) ?>'</h3>

    <div class="container">

      <?php foreach($videos as $video): ?>
        <div class="video">
          <h2><?= htmlspecialchars($video['title']) ?></h2>
          <h6><?= $video['publishedAt'] ?></h6>
          <p><?= htmlspecialchars($video['description']) ?></p>
        </div>
      <?php endforeach ?>
      
    </div>

    <br>
    <a href="index.php">Go back</a>
  </body>
</html>