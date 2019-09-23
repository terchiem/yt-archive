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

<div class="video-card sm">
  <a class="video-card-thumbnail" href="details.php?id=<?= $video['videoId'] ?>">
    <img 
      src="<?= getThumbnail($video['videoId']) ?>" 
      alt="<?= htmlspecialchars($video['title']) ?>"
    >
    <div class="video-card-duration">
      <?= formatDuration($video['duration']); ?>
    </div>
  </a>
  <div class="video-card-snippet">
    <a href="details.php?id=<?= $video['videoId'] ?>">
      <h3><?= $video['title'] ?></h3>
    </a>
    <div class="video-card-info">
      <a href="https://www.youtube.com/channel/<?= $video['channelId'] ?>">
        <?= htmlspecialchars($video['channelTitle']) ?>
      </a>
      <p><?= roundViews($video['viewCount']); ?> views</p>
    </div>
  </div>
</div>