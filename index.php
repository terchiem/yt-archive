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

    <div class="hero">
      <h1 class="center logo"><a href="/">Youtube Classic Video</a></h1>
  
      <form action="search.php" method="get" class="center">
        <input type="text" placeholder="Search" name="q" />
        <input type="submit" value="Submit" />
      </form>

      <nav>
        <ul>
          <li><a href="#">Browse</a></li>
          <li><a href="#">About</a></li>
        </ul>
      </nav>
    </div>

    <div class="container search-results">
      <h3>Recently searched videos</h3>
      <div class="video-list">
        <?php foreach($recentVideos as $video) {
          include 'templates/video-card.php';
        } ?>
      </div>
    </div>
    
    <?php include 'templates/footer.php' ?>
  </body>
</html>