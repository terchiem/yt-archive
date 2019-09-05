<?php
  function createSearchQuery($conn, $q) {
    return "SELECT * FROM videos WHERE title LIKE '%$q%'";
  }

  function extractVideoInfo($video) {
    return [
      'videoId' => $video['id']['videoId'],
      'channelId' => $video['snippet']['channelId'],
      'title' => $video['snippet']['title'],
      'description' => $video['snippet']['description'],
      'publishedAt' => $video['snippet']['publishedAt'],
      'channelTitle' => $video['snippet']['channelTitle']
    ];
  }

  function addVideos($conn, $searchResults) {
    foreach ($searchResults['items'] as $item) {
      addVideo($conn, $item);
    }
  }

  function addVideo($conn, $item) {
    $video = extractVideoInfo($item);

    // check of video id is in db
    $check = mysqli_query($conn, "SELECT id FROM `videos` WHERE `videoId` LIKE '${video['videoId']}'");
    if (mysqli_num_rows($check) == 0) {
      $channel_id = addChannel($conn, $video['channelId'], $video['channelTitle']);
      $insert = "INSERT INTO `videos`(`videoId`, `channel_id`, `title`, `description`, `publishedAt`) "
        ."VALUES("
          ."'${video['videoId']}', "
          ."'$channel_id', "
          ."'${video['title']}', "
          ."'${video['description']}', "
          ."'${video['publishedAt']}')";
        mysqli_query($conn, $insert);
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
?>