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
?>

<!DOCTYPE html>
<html>
  <head>
    <title><?= htmlspecialchars($video['title'])?> &mdash; Video</title>
    <?php include 'config/links.php'; ?>
  </head>

  <body>
    <?php include 'templates/header.php' ?>

    <div class="details-wrapper">
      <div class="details-main">
        <div class="container video-player">
          <iframe 
            width="640" height="360"
            src="https://www.youtube.com/embed/<?= $video['videoId'] ?>" 
            frameborder="0" 
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" 
            allowfullscreen>
          </iframe>
        </div>
    
        <div class="container video-details">
          <div class="video-details-header">
            <h2><?= $video['title'] ?></h2>
            <p><?= number_format($video['viewCount'], 0, 0, ',') ?> views</p>
            <div class="likes">
              <ul>
                <li>Likes: <?= $video['likeCount'] ?></li>
                <li>Dislikes: <?= $video['dislikeCount'] ?></li>
              </ul>
            </div>
          </div>
          <div class="video-details-info">
            <div class="channel-info">
              <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
                <?= htmlspecialchars($video['channelTitle']) ?>
              </a>
              <p class="date">Published on <?= getStringDate($video['publishedAt']) ?></p>
            </div>
            <p class="description"><?= $video['description'] ?></p>
          </div>
        </div>
      </div>
  
  
      <div class="container">
        <h3>Recently searched videos</h3>
        <div class="video-list recent">
          <?php foreach($recentVideos as $video) {
            include 'templates/video-card-sm.php';
          } ?>
        </div>
      </div>
    </div>
    
    <?php include 'templates/footer.php' ?>
  </body>
</html>


<?php mysqli_close($conn); ?>