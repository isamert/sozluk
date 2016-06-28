<?php
  $entry_id = $_GET['edit_entry'];
  $entry = Entry::get_entry($entry_id);
?>
<?php if(Member::check_permission($entry['member_id'])) { ?>
<div class="form-group">
    <form action="<?php echo Template::form_action_edit_entry(); ?>" method="post">
        <input type="hidden" name="entry_id" value="<?php echo $entry_id ?>" />
        <label for="entry_content">düzenleyiver:</label> <br />
        <?php include("html/bbcode_buttons.php"); ?>
        <textarea id="txt_entry_content" class="form-control content" rows="5" name="entry_content"><?php echo $entry['entry_content']; ?></textarea>
        <button name="edit_entry">gönder</button>
    </form>
</div>
<?php } ?>