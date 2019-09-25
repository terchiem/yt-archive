<?php
  /* ========================
          DB Queries
  ========================= */
  function createSearchQuery($query, $search, $page, $limit) {
    $criteria = $search ? 
      "JOIN video_tags ON videos.video_id = video_tags.video_id
      JOIN tags ON video_tags.tag_id = tags.tag_id
      WHERE `videos`.`title` LIKE '%$query%' OR `tags`.`tagName` = '$query'" :
      "WHERE `videos`.`category_id` = $query";
    $start = ($page - 1) * $limit;

    return "SELECT DISTINCT `videos`.*, `channels`.*, `categories`.`categoryName`
      FROM `videos` 
      LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
      LEFT JOIN `categories` ON `videos`.`category_id` = `categories`.`category_id`
      $criteria
      LIMIT $start, $limit";
  }

  function recentQuery($limit) {
    return "SELECT `videos`.*, `channels`.*
    FROM `videos` 
    LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
    ORDER BY `created_at` DESC
    LIMIT $limit";
  }

  function createVideoQuery($videoId) {
    return "SELECT `videos`.*, `channels`.*, `categories`.`categoryName`
      FROM `videos` 
      LEFT JOIN `channels` ON `videos`.`channel_id` = `channels`.`channel_id`
      LEFT JOIN `categories` ON `videos`.`category_id` = `categories`.`category_id`
      WHERE `videoId` = '$videoId'";
  }

  /* ========================
          DB Insertions
  ========================= */
  function addVideos($conn, $searchResults, $searchTerm = null) {
    foreach ($searchResults['items'] as $item) {
      addVideo($conn, $item, $searchTerm);
    }
  }

  function addVideo($conn, $item, $searchTerm) {
    $video = extractVideoInfo($conn, $item);
    $video_id = null;

    // check of video id is in db
    $check = mysqli_query($conn, "SELECT `video_id` FROM `videos` WHERE `videoId` LIKE '${video['videoId']}'");
    if ($check && mysqli_num_rows($check) == 0) {
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
    } else {
      $dbVideo = mysqli_fetch_assoc($check);
      $video_id = $dbVideo['video_id'];
    }

    // add search term to tag list
    if ($searchTerm && !is_numeric($searchTerm)) {
      addVideoTag($conn, $video_id, strtolower($searchTerm));
    }
  }

  function addChannel($conn, $channelId, $channelTitle) {
    // check of channel id is in db
    $check = mysqli_query($conn, "SELECT channel_id FROM `channels` WHERE `channelId` = '$channelId'");
    if ($check && mysqli_num_rows($check) == 0) {
      $insert = "INSERT INTO channels(channelId, channelTitle) "
        ."VALUES('$channelId', '$channelTitle')";
      mysqli_query($conn, $insert);
      return mysqli_insert_id($conn);
    }
    $channel = mysqli_fetch_assoc($check);
    return $channel['channel_id'];
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
    if ($check && mysqli_num_rows($check) == 0) {
      $insert = "INSERT INTO tags(tagName) VALUES('$tag')";
      mysqli_query($conn, $insert);
      return mysqli_insert_id($conn);
    }

    // add in a bool check
    $tag = mysqli_fetch_assoc($check);
    return $tag['tag_id'];
  }

  function addPageToken($conn, $token) {
    if (!$token) { return; }
    $check = mysqli_query($conn, "SELECT * FROM `page_tokens` WHERE `page_token` = '$token'");
    
    // add token to db if token does not exist
    if ($check && mysqli_num_rows($check) == 0) {
      $insert = "INSERT INTO page_tokens(page_token) VALUES('$token')";
      mysqli_query($conn, $insert);
    }
  }


  /* ========================
          DB Fetches
  ========================= */
  function getRecentVideos($conn, $limit) {
    $sql = recentQuery($limit);
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  function getCategoryName($conn, $id) {
    $sql = "SELECT `categories`.*
      FROM `categories` 
      WHERE `category_id` = $id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);
    return $category['categoryName'];
  }

  function getAllCategories($conn) {
    $sql = "SELECT * FROM `categories`";
    $result = mysqli_query($conn, $sql);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
  }

  function getNumResults($conn, $query, $search = true) {
    if ($search) {
      $join = 
        "JOIN video_tags ON videos.video_id = video_tags.video_id
        JOIN tags ON video_tags.tag_id = tags.tag_id";
      $criteria = "`videos`.`title` LIKE '%$query%' OR `tags`.`tagName` = '$query'";
    } else {
      $join = "";
      $criteria = "`videos`.`category_id` = '$query'";
    }
    
    $sql = "SELECT count(DISTINCT(videos.video_id)) AS total FROM videos 
      $join WHERE " . $criteria;

    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['total'];
  }

  function getPageToken($conn, $token_id) {
    $count = mysqli_query($conn, "SELECT * FROM `page_tokens`")->num_rows;
    if ($token_id > $count) {
      $token_id = $count;
    }
    
    $sql = "SELECT * FROM `page_tokens` WHERE `id` = $token_id";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    return $data['page_token'];
  }

  function checkCategory($conn, $id) {
    $check = mysqli_query($conn, "SELECT `category_id` FROM `categories` WHERE `category_id` = $id");
    return $check && mysqli_num_rows($check) > 0 ? true : false;
  }


  /* ========================
            Helpers
  ========================= */
  function extractVideoInfo($conn, $video) {
    $obj = [
      'videoId' => $video['id'],
      'channelId' => $video['snippet']['channelId'],
      'title' => mysqli_real_escape_string($conn, $video['snippet']['title']),
      'description' => mysqli_real_escape_string($conn, $video['snippet']['description']),
      'publishedAt' => $video['snippet']['publishedAt'],
      'channelTitle' => mysqli_real_escape_string($conn, $video['snippet']['channelTitle']),
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
?>