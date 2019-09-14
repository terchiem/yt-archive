<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/api_helpers.php';
  include 'helpers/view_helpers.php';

  $query = NULL;
  $result = NULL;

  if (isset($_GET["q"])) {
    $query = mysqli_real_escape_string($conn, $_GET["q"]);
    $sql = createSearchQuery($query);
    $result = mysqli_query($conn, $sql);

    if ($result->num_rows < 20) {
      // retrieve response JSON as array
      $searchResults = searchVideosAPI($query);

      // validate successful api call
      if(empty($searchResults['error'])) {
        addVideos($conn, $searchResults, $query);
        $result = mysqli_query($conn, $sql);
      } else {
        echo "Connection Error: " . $searchResults['error']['message'];
      }
    }
  } elseif (isset($_GET["category_id"])) {
    $query = mysqli_real_escape_string($conn, $_GET["category_id"]);
    $sql = selectCategoryQuery($query);
    $result = mysqli_query($conn, $sql);
    
    if ($result->num_rows < 20) {
      $searchResults = searchVideosAPI($query, true);

      // validate successful api call
      if (empty($searchResults['error'])) {
        addVideos($conn, $searchResults);
        $result = mysqli_query($conn, $sql);
      } else {
        echo "Connection Error: " . $searchResults['error']['message'];
      }
    }
    $query = getCategoryName($conn, $query);
  } else {
    header('Location: /yt-classic');
  }
    
  $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Search Results</title>
    <?php include 'config/links.php'; ?>
  </head>

  <body>
    <?php include 'templates/header.php'; ?>

    <div class="container search-results">
      <h3>Search results for '<?= htmlspecialchars($query) ?>' (<?= count($videos) ?>)</h3>
      <div class="video-list">
        <?php foreach($videos as $video) {
          include 'templates/video-card.php';
        } ?>
      </div>
    </div>

    <?php include 'templates/footer.php' ?>
  </body>
</html>

<?php mysqli_close($conn); ?>