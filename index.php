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

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php"><?php echo SITE_TITLE; ?></a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <!--<li class="active"><a href="#">Link <span class="sr-only">(current)</span></a></li>
        <li><a href="#">Link</a></li>-->
      </ul>
      <form class="navbar-form navbar-left" role="search">
		<fieldset>
			<div class="form-group">
				<input type="text" class="form-control typeahead" name="query" id="query" placeholder="başlık ara...">              
			</div>
			<button type="submit" class="btn btn-primary">git</button>
		</fieldset>
      </form>
      <ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">temalar şeysi <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="#" class="theme-select">black</a></li>
            <li><a href="#" class="theme-select">cyborg</a></li>
            <li><a href="#" class="theme-select">darkly</a></li>
            <li><a href="#" class="theme-select">flat</a></li>
			<li><a href="#" class="theme-select">sandstone</a></li>
			<li><a href="#" class="theme-select">ubuntu</a></li>
          </ul>
        </li>
		<?php if(!Member::is_signed()): ?>
        <li><a href="index.php?signup">kayıt ol</a></li>
		<?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
  
<div class="container-fluid text-center">
  <div class="row content">
    <div class="col-sm-2 sidenav">
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
    </div>
    <div class="col-sm-8 text-left">
	  <div class="row">
		<div id="notification_area" class="col-sm-12 text-left">
		  
		</div>
	  </div>
		<?php if(isset($_GET['topic_id'])):
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
				include("html_single_entry.php");
			}
		?>
			</ol>
			
		<?php if(Member::is_signed()) { ?>
		<div class="form-group">
			<form action="<?php echo Template::form_action_add_entry(); ?>" method="post">
				<input type="hidden" name="topic_id" value="<?php echo $topic_id ?>" />
				<label for="entry_content">bilgi ver:</label>
				<?php include("html_bbcode_buttons.php"); ?>
				<textarea id="txt_entry_content" class="form-control content" rows="5" name="entry_content"></textarea>
				<button name="add_entry">gönder</button>
			</form>
		</div>
		<?php } ?>
		<?php elseif(isset($_GET['entry_id'])):?>
		<ul>
			<?php
				$entry = Entry::get_entry($_GET['entry_id']);
				$member = Member::get_member($entry['member_id']);
				include("html_single_entry.php");
			?>
		</ul>
		<?php elseif(isset($_GET['cat_id'])):
		  $cat_id = $_GET['cat_id'];
		  $cat = Category::get_category($cat_id);
		?>
		<h1><?php echo htmlspecialchars(strtolower($cat['cat_name'])); ?></h1>
		<blockquote>
		  <p><?php echo htmlspecialchars(strtolower($cat['cat_description'])); ?></p>
		</blockquote>
		<h3>son başlıklar</h3>
		<?php
			  echo '<ul class="nav nav-pills nav-stacked">';
			  foreach(Category::get_topics($cat_id) as $topic)
				  echo "<li><a href=\"index.php?topic_id=" . $topic['topic_id'] . "\">" . htmlspecialchars(strtolower($topic["topic_name"])) . "</a></li>";
			  echo '</ul>';
		?>
		<?php elseif(isset($_GET['edit_entry'])):
		  $entry_id = $_GET['edit_entry'];
		  $entry = Entry::get_entry($entry_id);
		?>
		<?php if(Member::check_permission($entry['member_id'])) { ?>
		<div class="form-group">
			<form action="<?php echo Template::form_action_edit_entry(); ?>" method="post">
				<input type="hidden" name="entry_id" value="<?php echo $entry_id ?>" />
				<label for="entry_content">düzenleyiver:</label> <br />
				<?php include("html_bbcode_buttons.php"); ?>
				<textarea id="txt_entry_content" class="form-control content" rows="5" name="entry_content"><?php echo $entry['entry_content']; ?></textarea>
				<button name="edit_entry">gönder</button>
			</form>
		</div>
		<?php } ?>
		<?php elseif(isset($_GET['member_id'])):
			$member_id = $_GET['member_id'];
			$member = Member::get_member($member_id);
		?>
		<h2><?php echo htmlspecialchars($member['member_name']); ?></h2>
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
		<?php elseif(isset($_GET['messages'])): include("html_chat_box.php"); ?>
		<?php elseif(isset($_GET['query'])): ?>
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
				<?php include("html_bbcode_buttons.php"); ?>
				<textarea id="txt_entry_content" class="form-control" rows="5" name="entry_content"></textarea>
				<button name="add_topic">gönder</button>
			  </form>
		  </div>
		</div>
		<?php elseif(isset($_GET['login']) or isset($_GET['signin'])): Member::memberbox(); ?>
		<?php elseif(isset($_GET['register']) or isset($_GET['signup'])): Member::registerbox(); ?>
		<?php endif; ?>
		<!-- TODO: MESAJ ŞEYSİ BUNU DA TAŞI -->
		<div id="message_modal" class="modal fade" role="dialog">
		  <div class="modal-dialog">
			<div class="modal-content">
			  <div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">mesaj yolla</h4>
			  </div>
			  <div class="modal-body">
				  <form class="contact" name="message" id="form_message">
				  <div class="control-group">
					  <label class="control-label"  for="message_content">mesaj</label>
					  <div class="controls">
						  <textarea class="form-control" rows="5" id="message_content" name="message_content"></textarea>
					  </div>
				  </div>
				  <input id="member_id_receiver" type="hidden" name="member_id_receiver" value="" />
				  <input type="hidden" name="send_message" />
				</form>
			  </div>
			  <div class="modal-footer">
				<input class="btn btn-success" type="submit" value="sal!" id="send_message" name="send_message">
				<a href="#" class="btn" data-dismiss="modal">neyse</a>
			  </div>
			</div>
		
		  </div>
		</div>

    </div>
    <div class="col-sm-2 sidenav">
	  <?php
		  Member::memberbox();
	  ?>
    </div>
  </div>
</div>

</body>
</html>
