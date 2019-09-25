<?php
  $categories = getAllCategories($conn);
?>

<div class="categories container">
  <ul>
    <?php foreach ($categories as $category): ?>
      <li>
        <a href="search.php?category_id=<?= $category['category_id']; ?>"><?= $category['categoryName'] ?></a></li>
    <?php endforeach ?>
  </ul>
</div>