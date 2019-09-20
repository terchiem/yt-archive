<?php
  include 'config/db_connect.php';
  include 'helpers/db_helpers.php';
  include 'helpers/api_helpers.php';
  include 'helpers/view_helpers.php';

  // return to main page if no get parameters
  if (!isset($_GET['q']) && !isset($_GET['category_id'])) {
    header('Location: /yt-classic');
  }

  $validSearch = true;
  $search = isset($_GET['q']);

  // query check
  $query = $search ? $_GET['q'] : $_GET['category_id'];
  $query = mysqli_real_escape_string($conn, $query);

  // todo: query validation
    // display error or return to main page

  // pagination variables
  $videos_per_page = 20;
  $num_results = getNumResults($conn, $query, $search);
  $total_pages = ceil($num_results / $videos_per_page);
  if (!isset($_GET['page']) || !is_numeric($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'] > $total_pages ? $total_pages : $_GET['page'];
  }
  $page_link = $search ? "search.php?q=$query&page=" : 
    "search.php?category_id=$query&page=";
  $video_index_start = ($page - 1) * $videos_per_page;

  // search db for videos
  $sql = createSearchQuery($query, $search, $page, $videos_per_page);
  $result = mysqli_query($conn, $sql);

  // call api for more videos if results are less than limit
  if ($result->num_rows < $videos_per_page || 
    $video_index_start+$result->num_rows == $num_results) {
    // calculate id of page token and retrieve
    $token_id = ceil($num_results / 40);
    $token = $token_id ? getPageToken($conn, $token_id) : NULL;

    // retrieve video ids from search api call
    $searchResults = searchListAPI($query, $search, $token);

    // store page token if not in db
    $page_token = extractPageToken($searchResults);
    addPageToken($conn, $page_token);

    // use video ids to get video info for search results
    $videoResults = videoListAPI($searchResults);

    // validate successful api call
    if(empty($videoResults['error'])) {
      // add and retrieve videos from db
      addVideos($conn, $videoResults, $query);
      $result = mysqli_query($conn, $sql);

      // update numbers for videos added
      $num_results = getNumResults($conn, $query, $search);
      $total_pages = ceil($num_results / $videos_per_page);
    } else {
      echo "Connection Error: " . $videoResults['error']['message'];
    }
  }

  if (isset($_GET['category_id'])) {
    $query = getCategoryName($conn, $query);
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
      <h3>Search results for '<?= htmlspecialchars($query) ?>' 
      <span class="search-results-info">
        (showing <?= $video_index_start + 1 ?> - <?= $video_index_start + count($videos)?> 
        of <?= $num_results ?>)
      </span></h3>
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

    <section>
      <div class="page-list container center">
        <?php if ($page != 1): ?>
          <a href="<?= $page_link.'1'; ?>">«</a>
          <a href="<?= $page_link.($page-1 < 1 ? 1 : $page-1); ?>">‹</a>
        <?php else: ?>
          <p class="disabled">«</p>
          <p class="disabled">‹</p>
        <?php endif ?>
        <?php for ($page_num = 1; $page_num <= $total_pages; $page_num++): ?>
          <?php if ($page_num == $page): ?>
            <p class="current-page"><?= $page_num ?></p>
          <?php else: ?>
            <a href="<?= $page_link.$page_num ?>">
              <?= $page_num; ?>
            </a>
          <?php endif ?>
        <?php endfor ?>
        <?php if ($total_pages != $page): ?>
          <a href="<?= $page_link.($page+1 > $total_pages ? $total_pages : $page+1); ?>">›</a>
          <a href="<?= $page_link.$total_pages; ?>">»</a>
        <?php else: ?>
          <p class="disabled">›</p>
          <p class="disabled">»</p>
        <?php endif ?>
      </div>
    </section>

    <?php if (!$validSearch): ?>
      <div class="error-dialog">
        <p>Search terms must be at least 2 characters long.</p>
      </div>
    <?php endif ?>

    <?php include 'templates/footer.php' ?>
  </body>
</html>

<?php mysqli_close($conn); ?>