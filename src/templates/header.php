<header>
  <h2><a href="/">YT Archive</a></h2>
  <form action="search.php" method="get" class="searchbar">
    <input 
      id="search-text"
      type="text" 
      placeholder="Search" 
      name="q"
      value=<?= isset($_GET["q"]) ? htmlspecialchars($_GET["q"]) : ''; ?>
    >
    <button id="submit-btn" type="submit"><i class="fa fa-search"></i></button>
  </form>
  <div class="browse-menu">
    <button class="browse-btn">Browse</button>
    <button>About</button>
  </div>
</header>

<?php include 'templates/categories.php' ?>