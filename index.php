<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/view_helpers.php';

  $sql = recentQuery();
  $result = mysqli_query($conn, $sql);
  $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);
  mysqli_free_result($result);
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Youtube Classic</title>
    <link rel="stylesheet" href="style.css">
  </head>

  <body>

    <h1 class="center logo">Youtube Classic Video</h1>

    <form action="search.php" method="get" class="center">
      Search: <input type="text" name="q" />
      <input type="submit" value="Submit" />
    </form>

    <div class="container">
      <h3>Recently searched videos</h3>
      <div class="video-list">
        <?php foreach($videos as $video) {
          include 'templates/video-item.php';
        } ?>
      </div>
    </div>
    
  </body>
</html>