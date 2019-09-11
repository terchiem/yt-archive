<?php
  function createSearchQuery($q) {
    return "SELECT DISTINCT `videos`.*, `channels`.*
      FROM `videos` 
      LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
      JOIN video_tags ON videos.video_id = video_tags.video_id
      JOIN tags ON video_tags.tag_id = tags.tag_id
      WHERE `videos`.`title` LIKE '%$q%' OR `tags`.`tagName` = '%$q%'";
  }

  function recentQuery($limit) {
    return "SELECT `videos`.*, `channels`.*
    FROM `videos` 
    LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
    ORDER BY `created_at` DESC
    LIMIT $limit";
  }

  function selectVideoQuery($videoId) {
    return "SELECT `videos`.*, `channels`.*, `categories`.`categoryName`
      FROM `videos` 
      LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
      LEFT JOIN `categories` ON `videos`.`category_id` = `categories`.`category_id`
      WHERE `videoId` = '$videoId'";
  }

  function extractVideoInfo($video) {
    $obj = [
      'videoId' => $video['id'],
      'channelId' => $video['snippet']['channelId'],
      'title' => $video['snippet']['title'],
      'description' => $video['snippet']['description'],
      'publishedAt' => $video['snippet']['publishedAt'],
      'channelTitle' => $video['snippet']['channelTitle'],
      'tags' => $video['snippet']['tags'],
      'category_id' => $video['snippet']['categoryId'],
      'duration' => $video['contentDetails']['duration'],
      'viewCount' => $video['statistics']['viewCount']
    ];

    if (array_key_exists('likeCount', $video['statistics'])) {
      $obj['likeCount'] = $video['statistics']['likeCount'];
      $obj['dislikeCount'] = $video['statistics']['dislikeCount'];
    } else {
      $obj['likeCount'] = 0;
      $obj['dislikeCount'] = 0;
    }
    return $obj;
  }

  function addVideos($conn, $searchResults) {
    foreach ($searchResults['items'] as $item) {
      addVideo($conn, $item);
    }
  }

  function addVideo($conn, $item) {
    $video = extractVideoInfo($item);

    // check of video id is in db
    $check = mysqli_query($conn, "SELECT `video_id` FROM `videos` WHERE `videoId` LIKE '${video['videoId']}'");
    if (mysqli_num_rows($check) == 0) {
      $channel_id = addChannel($conn, $video['channelId'], $video['channelTitle']);
      $insert = "INSERT INTO `videos`(`videoId`, `channel_id`, `title`, `description`, `publishedAt`, category_id, duration, viewCount, likeCount, dislikeCount) 
        VALUES(
          '${video['videoId']}', 
          '$channel_id', 
          '${video['title']}', 
          '${video['description']}', 
          '${video['publishedAt']}',
          '${video['category_id']}',
          '${video['duration']}',
          '${video['viewCount']}',
          '${video['likeCount']}',
          '${video['dislikeCount']}')";
      mysqli_query($conn, $insert);
      $video_id = mysqli_insert_id($conn);
      addVideoTags($conn, $video_id, $video['tags']);
    } 
  }

  function addChannel($conn, $channelId, $channelTitle) {
    // check of channel id is in db
    $check = mysqli_query($conn, "SELECT channel_id FROM `channels` WHERE `channelId` = '$channelId'");
    if (mysqli_num_rows($check) == 0) {
      $insert = "INSERT INTO channels(channelId, channelTitle) "
        ."VALUES('$channelId', '$channelTitle')";
      mysqli_query($conn, $insert);
      return mysqli_insert_id($conn);
    }
    $channel = mysqli_fetch_assoc($check);
    return $channel['channel_id'];
  }

  function addVideoTags($conn, $video_id, $tags) {
    // set max tag limit
    $limit = count($tags) > 5 ? 5 : count($tags);

    for ($i = 0; $i < $limit; $i++) {
      addVideoTag($conn, $video_id, strtolower($tags[$i]));
    }
  }

  function addVideoTag($conn, $video_id, $tag) {
    $tag_id = addTag($conn, $tag);
    $insert = "INSERT INTO video_tags(video_id, tag_id)
      VALUES('$video_id', '$tag_id')";
    mysqli_query($conn, $insert);
  }

  function addTag($conn, $tag) {
    $check = mysqli_query($conn, "SELECT tag_id FROM `tags` WHERE `tagName` = '$tag'");
    
    // add tag to db if tag does not exist
    if (mysqli_num_rows($check) == 0) {
      $insert = "INSERT INTO tags(tagName) VALUES('$tag')";
      mysqli_query($conn, $insert);
      return mysqli_insert_id($conn);
    }
    $tag = mysqli_fetch_assoc($check);
    return $tag['tag_id'];
  }

  function getRecentVideos($conn, $limit) {
    $sql = recentQuery($limit);
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }



  ///////////////////////////////////////////////////////////////////

  // function createSearchQuery($q) {
  //   return "SELECT `videos`.*, `channels`.*
  //     FROM `videos` 
  //     LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
  //     WHERE title LIKE '%$q%' OR `description` LIKE '%$q%'";
  // }
?>