<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/view_helpers.php';

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
    <h2 class="center logo">Youtube Classic Video</h1>

    <form action="search.php" method="get" class="center">
      Search: <input type="text" name="q" />
      <input type="submit" value="Submit" />
    </form>

    <div class="container">
      <h3>Search results for '<?= htmlspecialchars($q) ?>'</h3>
      <div class="video-list">
        <?php foreach($videos as $video) {
          include 'templates/video-item.php';
        } ?>
      </div>
    </div>

    <br>
    <a href="index.php">Go back</a>
  </body>
</html>