<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/api_helpers.php';
  include 'helpers/view_helpers.php';

  $query = isset($_GET["q"]) ? $_GET["q"] : '';
  $result = NULL;
  $validSearch = true;

  if (isset($_GET["q"])) {
    if (strlen($_GET["q"]) < 3) {
      $validSearch = false;
    } else {
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
    
  if ($result) {
    $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
  } else {
    $videos = [];
  }
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
        <?php if ($videos): ?>
            <?php foreach($videos as $video) {
              include 'templates/video-card.php';
            } ?>
        <?php else: ?>
          <div class="empty-search">
            <p>Could not find any results!</p>
          </div>
        <?php endif ?>
      </div>
    </div>


    <div class="page-list">
        <ul>
        <!-- Add in pagination -->
        </ul>
    </div>


    <?php if (!$validSearch): ?>
      <div class="error-dialog">
        <p>Search terms must be at least 2 characters long.</p>
      </div>
    <?php endif ?>

    <?php include 'templates/footer.php' ?>
  </body>
</html>

<?php mysqli_close($conn); ?>