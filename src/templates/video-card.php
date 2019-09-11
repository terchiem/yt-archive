<?php
  // $video
  //   'title'
  //   'description'
  //   'publishedAt'
  //   'videoId'
  //   'channelId'
  //   'channelTitle'
  //   'viewCount'
  //   'likeCount'
  //   'dislikeCount'
  //   'duration'
  //   'category_id'
  //   'categoryName'
?>


<div class="video-card md">
  <a href="details.php?id=<?= $video['videoId'] ?>">
    <img src="<?= getThumbnail($video['videoId']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
  </a>

  <div class="video-card-snippet">
    <a href="details.php?id=<?= $video['videoId'] ?>">
      <h3><?= $video['title'] ?></h3>
    </a>
    <div class="video-card-info">
      <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
        <?= htmlspecialchars($video['channelTitle']) ?>
      </a> • <?= roundViews($video['viewCount']); ?> views
      • Published <?= getStringDate($video['publishedAt']) ?>
      • Duration <?= formatDuration($video['duration']); //temp?>
      <p class="video-card-description"><?= htmlspecialchars($video['description']) ?></p>
    </div>
  </div>
</div>