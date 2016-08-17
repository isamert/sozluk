<?php
require_once("config.php");
require_once("topic.php");
require_once("member.php");
require_once("category.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo SITE_TITLE; ?></title>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="themes/css/<?php echo Template::get_css(); ?>" />
  <link rel="stylesheet" href="themes/css/typeahead.css" />
  <script type='text/javascript' src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script type='text/javascript' src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <script type='text/javascript' src="themes/js/typeahead.js"></script>
  <script type='text/javascript' src="themes/js/index.js"></script>
</head>
  <body>
	<?php include("html/navbar.php"); ?>
	
	<div class="container-fluid text-center">
	  <div class="row content">
		<div class="col-sm-2 sidenav">
		  <?php include("html/left_frame.php"); ?>
		</div>
		
		<div class="col-sm-8 text-left">
		  <div class="row">
			<div id="notification_area" class="col-sm-12 text-left">
			  
			</div>
		  </div>
			<?php if(isset($_GET['topic_id'])): ?>
			  <?php include("html/topic.php"); ?>
			<?php elseif(isset($_GET['entry_id'])):?>
			  <?php include("html/entry.php"); ?>
			<?php elseif(isset($_GET['cat_id'])): ?>
			  <?php include("html/category.php"); ?>
			<?php elseif(isset($_GET['edit_entry'])): ?>
			  <?php include("html/edit_entry.php"); ?>
			<?php elseif(isset($_GET['member_id'])): ?>
			  <?php include("html/member_page.php"); ?>
			<?php elseif(isset($_GET['messages'])): ?>
			  <?php include("html/chat_box.php"); ?>
			<?php elseif(isset($_GET['query'])): ?>
			  <?php include("html/open_topic.php") ?>
			<?php elseif(isset($_GET['login']) or isset($_GET['signin'])): ?>
			  <?php Member::memberbox(); ?>
			<?php elseif(isset($_GET['register']) or isset($_GET['signup'])):?>
			  <?php Member::registerbox(); ?>
			<?php endif; ?>
			
			<?php include("html/message_modal.php"); ?>
	
		</div>
		<div class="col-sm-2 sidenav">
		  <?php Member::memberbox(); ?>
		</div>
	  </div>
	</div>
  
  </body>
</html>
