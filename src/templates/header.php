<header>
  <h2><a href="/">YouTube Classic</a></h2>
  <form action="search.php" method="get" class="searchbar">
    <input 
      id="searchbar"
      type="text" 
      placeholder="Search" 
      name="q"
      value=<?= isset($_GET["q"]) ? htmlspecialchars($_GET["q"]) : ''; ?>
    >
    <input id="submit-btn" type="submit" value="Search"/>
  </form>
  <div class="browse-menu">
    <button class="browse-btn">Browse</button>
    <button>About</button>
  </div>
</header>

<?php include 'templates/categories.php' ?>