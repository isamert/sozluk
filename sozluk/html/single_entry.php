<?php
    $li_class = "";
    if(isset($_GET["populate"]) && $_GET["populate"] == $entry['entry_id']) {
        $li_class = 'class="bg-info"';
    }
?>

<li <?php echo $li_class ?>>
    <?php echo TextManipulation::entry($entry['entry_content']); ?>
    <div class='text-right'>
        <a class="text-muted show_reply" entry_id="<?php echo $entry["entry_id"]; ?>" style="cursor:pointer">cevapları göster (<?php echo Entry::get_reply_count($entry['entry_id']); ?>) </a>
        (<?php echo $entry['entry_date']; ?>) 
        <span class='glyphicon glyphicon-menu-up vote' entry_vote="up" entry_id="<?php echo $entry['entry_id']; ?>"><?php echo $entry['entry_up_vote']; ?></span>
        <span class='glyphicon glyphicon-menu-down vote' entry_vote="down" entry_id="<?php echo $entry['entry_id']; ?>"><?php echo $entry['entry_down_vote'] ?></span>
        <a href='index.php?member_id=<?php echo $member['member_id']?>'><?php echo htmlspecialchars($member['member_name']); ?></a>
        <div class="btn-group">
            <a href="#" class="btn btn-xs dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <!--<li><a href="#" class="show_reply" entry_id="<?php echo $entry['entry_id']; ?>">cevapları göster</a></li> -->
              <li><a href="#" class="btn-send-message" data-toggle="modal" data-target="#message_modal" member_id="<?php echo $member['member_id']; ?>">mesaj yolla</a></li>
              <li class="divider"></li>
              <li><a href="index.php?entry_id=<?php echo $entry['entry_id']; ?>">entry linki</a></li>
              <?php if(Member::check_permission($member['member_id'])) { ?>
                <li class="divider"></li>
                <li><a href="index.php?edit_entry=<?php echo $entry['entry_id']; ?>">düzenle</a></li>
                <li><a href="entry.php?operation=remove&entry_id=<?php echo $entry['entry_id']; ?>">sil</a></li>
              <?php } ?>
            </ul>
      </div>
    </div>
    <ol id="reply_<?php echo $entry["entry_id"]; ?>">
        
    </ol>
</li>