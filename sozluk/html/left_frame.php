
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">yeniler</a></li>
  <li><a data-toggle="tab" href="#menu1">takip</a></li>
</ul>
<div class="tab-content text-left">
  <div id="home" class="tab-pane fade in active">
    <h3>günün başlıkları</h3>
    <?php
      $current_page = 1;
      if(isset($_GET['topics_page'])) {
        $current_page = $_GET['topics_page'];
      }
      echo '<ul class="nav nav-pills nav-stacked">';
      foreach(Topic::get_latest_updated($current_page) as $topic)
          echo "<li><a href=\"index.php?topic_id=" . $topic['topic_id'] . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . "</a></li>";
      echo '</ul>';
    ?>
  <ul class="pager">
    <li class="previous <?php echo $current_page == 1 ? "disabled":""; ?>"><a href="index.php?topics_page=<?php echo $current_page - 1; ?>">&larr; daha yeni</a></li>
    <li class="next"><a href="index.php?topics_page=<?php echo $current_page + 1; ?>">daha eski &rarr;</a></li>
  </ul>
  </div>
  <div id="menu1" class="tab-pane fade">
    <h3>takip ettiklerim</h3>
    
    <?php
        if(Member::is_signed()) {
            echo '<ul class="nav nav-pills nav-stacked">';
            foreach(Member::get_subbed_categories(Member::get_signed_member_id()) as $cat_id)
                foreach(Category::get_topics($cat_id) as $topic)
                    echo "<li><a href=\"#\">" . $topic["topic_id"] . " - " . htmlspecialchars(strtolower($topic['topic_name'])) . "</a></li>";
            echo '</ul>';
        }
        else
            echo "<p>takip ettiklerinizi görebilmeniz için giriş yapmalısınız.</p>"
    ?>
  </div>
</div>
