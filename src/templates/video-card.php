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
    <div class="video-card-thumbnail">
      <img src="<?= getThumbnail($video['videoId']) ?>" alt="<?= htmlspecialchars($video['title']) ?>">
      <div class="video-card-duration">
        <?= formatDuration($video['duration']); ?>
      </div>
    </div>
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
      <p class="video-card-description"><?= htmlspecialchars($video['description']) ?></p>
    </div>
  </div>
</div>