<legend>buldukarımız</legend>
<?php
  $query = strtolower(htmlspecialchars(trim($_GET['query'])));
  foreach(Topic::search_all($query) as $topic) {
    if(strtolower(htmlspecialchars($topic['topic_name'])) == $query)
      PhpOperations::redirect(PathOperations::join(SITE_ADDRESS, "index.php?topic_id=" . $topic['topic_id']));
    else {?>
      <ul class="nav nav-pills nav-stacked">
      <?php
          echo "<li><a href=\"index.php?topic_id=" . $topic['topic_id'] . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . "</a></li>";
      ?>
      </ul>
<?php }
  } ?>
  
  
<br />
<br />

<div class="well">
  <legend>başlık aç</legend>
  <h3>belki siz "<strong class="text-primary"><?php echo $query; ?></strong>" adında bir başlık açmak istersiniz?</h3>
  <div class="form-group">
      <form action="<?php echo Template::form_action_add_topic(); ?>" method="post">
        <input type="hidden" name="topic_name" value="<?php echo $query; ?>" />
        <input type="hidden" id="selected_cat_id" name="cat_id" value="" />
        <label for="">kategori seç: </label>
        <input type="text" class="form-control typeahead" name="cat_name" id="cat_finder" placeholder="kategori bulmak için yazmaya başlayın..." autocomplete="off"/>
        <div id="found_categories" class="well well-sm"></div>
        <label for="entry_content">bilgi ver:</label>
        <?php include("html/bbcode_buttons.php"); ?>
        <textarea id="txt_entry_content" class="form-control" rows="5" name="entry_content"></textarea>
        <button name="add_topic">gönder</button>
      </form>
  </div>
</div>