<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/view_helpers.php';

  if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET["id"]);
    $sql = selectVideoQuery($id);
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {
      //id found
      $video = mysqli_fetch_assoc($result);
      
    } else {
      //id not found
      header('Location: /yt-classic');
    }
    mysqli_free_result($result);
  } else {
    header('Location: /yt-classic');
  }

  $recentVideos = getRecentVideos($conn, 10);
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?= htmlspecialchars($video['title'])?> &mdash; Video</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
  </head>

  <body>

    <h1 class="center logo"><a href="/yt-classic">Youtube Classic Video</a></h1>

    <div class="video-view">
      <iframe 
        width="560" height="315" 
        src="https://www.youtube.com/embed/<?= $video['videoId'] ?>" 
        frameborder="0" 
        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
        allowfullscreen>
      </iframe>
      <h2><?= $video['title'] ?></h2>
      <h4>
        <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
          <?= htmlspecialchars($video['channelTitle']) ?>
        </a> • <?= $video['publishedAt'] ?>
      </h4>
      <p><?= $video['description'] ?></p>
    </div>


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