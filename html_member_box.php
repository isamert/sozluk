<ul class="nav nav-pills nav-stacked">
  <li class="active"><a href="index.php?member_id=<?php echo Member::get_signed_member_id(); ?>">ben</a></li>
  <li><a href="index.php?messages">mesajlar<span class="badge"><?php echo Member::get_unread_message_count(Member::get_signed_member_id()); ?></span></a></li>
  <li><a href="#">ayarlar</a></li>
  <li><a href="member.php?operation=signout">çıkış</a></li>
</ul>

<div class="well">bir şeyler olabilir burada</div>