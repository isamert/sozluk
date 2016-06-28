<div class="col-sm-3">
    <div class="list-group">
      <legend>konuşmalar</legend>
      <?php
      $conversation_groups = iterator_to_array(Member::get_message_sender_members(Member::get_signed_member_id())) +
                             iterator_to_array(Member::get_message_receiver_members(Member::get_signed_member_id()));
      foreach($conversation_groups as $conversation) { ?>
      <a href="index.php?messages&member_id_sender=<?php echo $conversation['member_id']; ?>" class="list-group-item">
        <h4 class="list-group-item-heading"><?php echo htmlspecialchars($conversation['member_name']); ?></h4>
        <p class="list-group-item-text"><?php echo htmlspecialchars(htmlspecialchars($conversation['message_content'])); ?></p>
      </a>
      <?php } ?>
    </div>
</div>
<div class="col-sm-9">
    <?php
    if(isset($_GET['member_id_sender'])): ?>
    <legend>konuşma</legend>
    <?php
        $member_id_sender = $_GET['member_id_sender'];
        $member_name_sender = Member::get_member($member_id_sender)['member_name'];
        
        Member::set_messages_all_read(Member::get_signed_member_id(), $member_id_sender);
        
        foreach(Member::get_conversation(Member::get_signed_member_id(), $member_id_sender) as $message)  { ?>
            <blockquote <?php echo Member::get_signed_member_id() == $message['member_id_sender'] ? 'class="blockquote-reverse"':""; ?>>
              <p><?php echo htmlspecialchars($message['message_content']); ?></p>
              <small><cite title="gönderen"><?php echo htmlspecialchars($message['member_id_sender'] == $member_id_sender ? $member_name_sender:Member::get_signed_member_name()); ?></cite></small>
            </blockquote>
    <?php } ?>
    <a href="#" class="btn btn-success btn-send-message btn-lg btn-block" data-toggle="modal" data-target="#message_modal" member_id="<?php echo $member_id_sender ?>">cevap ver</a>
    <br />
    <br />
    <?php endif; ?>
</div>