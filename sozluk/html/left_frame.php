<?php
    $current_page = 1;
    if(isset($_GET['topics_page'])) {
        $current_page = $_GET['topics_page'];
    }
    
    $title = "günün başlıkları";
    $show_entries = false;
    if(isset($_GET['member_id']) && isset($_GET['show_entries'])) {
        $show_entries = true;
        $member_id = $_GET['member_id'];
        $title = Member::get_member($member_id)['member_name'] . "'in entryleri";
    }
      
    $url_next = PathOperations::append_query(PhpOperations::get_current_url(), "topics_page", $current_page + 1);
    $url_prev = PathOperations::append_query(PhpOperations::get_current_url(), "topics_page", $current_page - 1);
?>

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#home">yeniler</a></li>
  <li><a data-toggle="tab" href="#menu1">takip</a></li>
</ul>
<div class="tab-content text-left">
  <div id="home" class="tab-pane fade in active">
    <h3><?php echo $title; ?></h3>
        <ul class="nav nav-pills nav-stacked">
    <?php
      if ($show_entries) {
          foreach(Member::get_all_entries($member_id, $current_page) as $entry) {
              $topic = Entry::get_topic($entry['entry_id']);
              $url = PathOperations::append_query(PhpOperations::get_current_url(), "topic_id", $topic['topic_id']);
              $url = PathOperations::append_query($url, "populate", $entry['entry_id']);
              echo "<li><a href=\"" . $url . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . '/#'. $entry['entry_id'] . "</a></li>";
          }
      
      } else {
          foreach(Topic::get_latest_updated($current_page) as $topic)
              echo "<li><a href=\"index.php?topic_id=" . $topic['topic_id'] . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . "</a></li>";
      }
    ?>
    </ul>
  <ul class="pager">
    <li class="previous <?php echo $current_page == 1 ? "disabled":""; ?>"><a href="<?php echo $url_prev ?>">&larr; daha yeni</a></li>
    <li class="next"><a href="<?php echo $url_next ?>">daha eski &rarr;</a></li>
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
