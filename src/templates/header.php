<header>
  <a href="/">
    <img class="logo" src="assets/logo.png" alt="YT Archive">
  </a>
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
    <button id="browse-btn">Browse</button>
    <button id="about-btn">About</button>
  </div>
</header>

<?php 
  include 'templates/categories.php';
  include 'templates/about.php'; 
?>