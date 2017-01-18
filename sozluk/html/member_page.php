<?php
    $member_id = $_GET['member_id'];
    $member = Member::get_member($member_id);
?>

<header>
    <h2><?php echo htmlspecialchars($member['member_name']); ?></h2>
    <span class="label label-default"><?php echo Member::get_entry_count($member_id) ?> &middot;</span>
</header>

<div class="col-sm-12">
  <a href="#" class="btn btn-success btn-sm btn-send-message" data-toggle="modal" data-target="#message_modal" member_id="<?php echo $member['member_id'] ?>">mesaj sal</a>
  <a href="#" class="btn btn-info btn-sm">takip et</a>
  <a href="#" class="btn btn-warning btn-sm">şikayet et</a>
</div>
<div class="col-sm-6">
    <h3>son entryleri</h3>
    <ul>
        <?php foreach(Member::get_latest_entries($member_id) as $entry) {
                $topic = Entry::get_topic($entry['entry_id'])
        ?>
            <li><a href="index.php?topic_id=<?php echo $topic['topic_id']; ?>&populate=<?php echo $entry['entry_id'] ?>"><?php echo htmlspecialchars(strtolower($topic['topic_name'])); ?></a></li>
        <?php } ?>
    </ul>
</div>
<div class="col-sm-6">
  <h3>şeyler</h3>
  <div>
    buraya da bi şeyler gelecek. son beğendikleri falan olabilir.
  </div>
</div>