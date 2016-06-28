<?php
    $topic_id = $_GET['topic_id'];
    $topic = Topic::get_topic($topic_id);
    $category = Category::get_category($topic['cat_id']);
?>
    <ul class="breadcrumb">
    <li><a href="<?php echo "?cat_id=" . $category["cat_id"]; ?>"><?php echo htmlspecialchars(strtolower($category['cat_name'])); ?></a></li>
    <li class="active"><?php echo htmlspecialchars(strtolower($topic['topic_name'])) ?></li>
    </ul>
    
    <h2><?php echo htmlspecialchars(strtolower($topic['topic_name'])); ?></h2>
    <ol>
    <?php
        foreach (Topic::get_entries($topic_id) as $entry) {
            $member = Member::get_member($entry['member_id']);
            include("html/single_entry.php");
        }
    ?>
    </ol>
    
<?php if(Member::is_signed()) { ?>
<div class="form-group">
    <form action="<?php echo Template::form_action_add_entry(); ?>" method="post">
        <input type="hidden" name="topic_id" value="<?php echo $topic_id ?>" />
        <label for="entry_content">bilgi ver:</label>
        <?php include("html/bbcode_buttons.php"); ?>
        <textarea id="txt_entry_content" class="form-control content" rows="5" name="entry_content"></textarea>
        <button name="add_entry">gönder</button>
    </form>
</div>
<?php } ?>