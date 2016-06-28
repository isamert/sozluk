<?php
  $cat_id = $_GET['cat_id'];
  $cat = Category::get_category($cat_id);
?>
<h1><?php echo htmlspecialchars(strtolower($cat['cat_name'])); ?></h1>
<blockquote>
  <p><?php echo htmlspecialchars(strtolower($cat['cat_description'])); ?></p>
</blockquote>
<h3>son başlıklar</h3>
<ul class="nav nav-pills nav-stacked">
<?php
      foreach(Category::get_topics($cat_id) as $topic)
          echo "<li><a href=\"index.php?topic_id=" . $topic['topic_id'] . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . "</a></li>";
?>
</ul>