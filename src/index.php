<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/view_helpers.php';

  $recentVideos = getRecentVideos($conn, 20);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>YT Archive</title>
    <?php include 'config/links.php'; ?>
  </head>

  <body>

    <div class="hero">
      <a href="/">
        <img class="logo" src="assets/logo.png" alt="YT Archive">
      </a>
  
      <form action="search.php" method="get" class="searchbar center">
        <input id="search-text" type="text" placeholder="Search" name="q" />
        <button id="submit-btn" type="submit"><i class="fa fa-search"></i></button>
      </form>

      <div class="browse-menu">
        <button id="browse-btn">Browse</button>
        <button id="about-btn">About</button>
      </div>
    </div>
    
    <?php include 'templates/categories.php' ?>

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

<?php mysqli_close($conn); ?>