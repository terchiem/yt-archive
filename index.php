<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/view_helpers.php';

  $recentVideos = getRecentVideos($conn, 20);
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Youtube Classic</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
  </head>

  <body>

    <h1 class="center logo"><a href="/yt-classic">Youtube Classic Video</a></h1>

    <form action="search.php" method="get" class="center">
      Search: <input type="text" name="q" />
      <input type="submit" value="Submit" />
    </form>

    <div class="container">
      <h3>Recently searched videos</h3>
      <div class="video-list">
        <?php foreach($recentVideos as $video) {
          include 'templates/video-item.php';
        } ?>
      </div>
    </div>
    
  </body>
</html>