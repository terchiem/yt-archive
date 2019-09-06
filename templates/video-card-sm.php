<?php
  // $video
  //   'title'
  //   'description'
  //   'publishedAt'
  //   'videoId'
  //   'channelId'
  //   'channelTitle'
?>

<div class="video-card sm">
  <a href="details.php?id=<?= $video['videoId'] ?>">
    <img 
      src="<?= getThumbnail($video['videoId']) ?>" 
      alt="<?= htmlspecialchars($video['title']) ?>"
    >
  </a>
  <div class="video-card-snippet">
    <a href="details.php?id=<?= $video['videoId'] ?>">
      <h3><?= $video['title'] ?></h3>
    </a>
    <div class="video-card-info">
      <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
        <?= htmlspecialchars($video['channelTitle']) ?>
      </a>
      <p>{ VIEW COUNT }</p>
    </div>
  </div>
</div>