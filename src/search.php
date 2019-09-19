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
  

  // determine if searching by term or category
  $search = isset($_GET['q']) ? true : false;

  // query check
  $query = $search ? $_GET['q'] : $_GET['category_id'];
  $query = mysqli_real_escape_string($conn, $query);

  // todo: query validation
    // display error or return to main page

  // pagination variables
  $videos_per_page = 20;
  if (!isset($_GET['page'])) {
    $page = 1;
  } else {
    $page = $_GET['page'];
  }
  $num_results = getNumResults($conn, $query, $search);
  $total_pages = ceil($num_results / $videos_per_page);
  $page_link = $search ? "search.php?q=$query&page=" : 
    "search.php?category_id=$query&page=";
  $video_index_start = ($page - 1) * $videos_per_page;

  $sql = createSearchQuery($query, $search, $page, $videos_per_page);
  $result = mysqli_query($conn, $sql);

  // search for more videos if results are less than limit
  // if ($result->num_rows < $videos_per_page) {
  //   // retrieve response JSON as array
  //   $searchResults = searchVideosAPI($query, $search);

  //   // validate successful api call
  //   if(empty($searchResults['error'])) {
  //     addVideos($conn, $searchResults, $query);
  //     $result = mysqli_query($conn, $sql);
  //   } else {
  //     echo "Connection Error: " . $searchResults['error']['message'];
  //   }
  // }

  if (isset($_GET['category_id'])) {
    $query = getCategoryName($conn, $query);
  }

  if ($result) {
    $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_free_result($result);
  } else {
    $videos = [];
  }

  ///////////////////////////////////////////////

  // if ($search) {
  //   if (strlen($query) < 2) {
  //     $validSearch = false;
  //   } else {
  //     $query = mysqli_real_escape_string($conn, $query);

  //     // get number of pages
  //     $total_pages = ceil(getNumResults($conn, $query) / $videos_per_page);

  //     // query for current page range
  //     $sql = createSearchQuery($query); // add limit
  //     $result = mysqli_query($conn, $sql);

  //     if ($result->num_rows < 20) {
  //       // retrieve response JSON as array
  //       $searchResults = searchVideosAPI($query);

  //       // validate successful api call
  //       if(empty($searchResults['error'])) {
  //         addVideos($conn, $searchResults, $query);
  //         $result = mysqli_query($conn, $sql);
  //       } else {
  //         echo "Connection Error: " . $searchResults['error']['message'];
  //       }
  //     }
  //   }
  // } elseif (isset($_GET["category_id"])) {
  //   $query = mysqli_real_escape_string($conn, $_GET["category_id"]);
  //   $sql = createCategoryQuery($query);
  //   $result = mysqli_query($conn, $sql);
    
  //   if ($result->num_rows < 20) {
  //     $searchResults = searchVideosAPI($query, true);

  //     // validate successful api call
  //     if (empty($searchResults['error'])) {
  //       addVideos($conn, $searchResults);
  //       $result = mysqli_query($conn, $sql);
  //     } else {
  //       echo "Connection Error: " . $searchResults['error']['message'];
  //     }
  //   }
  //   $query = getCategoryName($conn, $query);
  // } else {
  //   header('Location: /yt-classic');
  // }
    
  // if ($result) {
  //   $videos = mysqli_fetch_all($result, MYSQLI_ASSOC);
  //   mysqli_free_result($result);
  // } else {
  //   $videos = [];
  // }
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


    <div class="page-list center">
      <ul class="container">
        <?php for ($page_num = 1; $page_num <= $total_pages; $page_num++): ?>
          <li>
            <?php if ($page_num == $page): ?>
              <p><?= $page_num; ?></p>
            <?php else: ?>
              <a href="<?= $page_link.$page_num ?>"><?= $page_num ?></a>
            <?php endif ?>
          </li>
        <?php endfor ?>
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