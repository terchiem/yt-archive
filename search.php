
<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/api_helpers.php';
  include 'helpers/view_helpers.php';

  $q = mysqli_real_escape_string($conn, $_GET["q"]);
  $sql = createSearchQuery($q);
  $result = mysqli_query($conn, $sql);

  if ($result->num_rows < 20) {
    echo 'less than 20 results';  // debug

    // retrieve response JSON as array
    $searchResults = callSearchApi($q);

    // validate successful api call
    if(empty($searchResults['error'])) {
      addVideos($conn, $searchResults);
      $result = mysqli_query($conn, $sql);
    } else {
      echo "Connection Error: " . $searchResults['error']['message'];
    }
  }
    
  $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);

  mysqli_free_result($result);
  mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Search Results</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="style.css">
  </head>

  <body>
    <?php include 'templates/header.php'; ?>

    <div class="container search-results">
      <h3>Search results for '<?= htmlspecialchars($q) ?>' (<?= count($videos) ?>)</h3>
      <div class="video-list">
        <?php foreach($videos as $video) {
          include 'templates/video-card.php';
        } ?>
      </div>
    </div>

    <?php include 'templates/footer.php' ?>
  </body>
</html>