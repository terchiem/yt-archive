<?php
  // $video
  //   'title'
  //   'description'
  //   'publishedAt'
  //   'videoId'
  //   'channelId'
  //   'channelTitle'
?>


<div class="video-item">
  <a href="details.php?id=<?= $video['videoId'] ?>">
    <img src="<?= getThumbnail($video['videoId']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
  </a>

  <div class="video-snippet">
    <a href="details.php?id=<?= $video['videoId'] ?>">
      <h2><?= $video['title'] ?></h2>
    </a>
    <h6>
      <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
        <?= htmlspecialchars($video['channelTitle']) ?>
      </a> • <?= $video['publishedAt'] ?></h6>
    <p><?= htmlspecialchars($video['description']) ?></p>
  </div>
</div>