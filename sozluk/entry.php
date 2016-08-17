<?php
require_once("connection.php");
require_once("member.php");

if(isset($_GET['operation'])) {
    $operation = $_GET['operation'];
    
    if ($operation == "add") {
        if(Member::is_signed() && isset($_POST['add_entry'])) {
            $entry_content = $_POST['entry_content'];
            $topic_id = $_POST['topic_id'];
            
            $entry_id = Entry::add_topic(Member::get_signed_member_id(), $topic_id, $entry_content);
            if($entry_id)
				Template::show_warning("entry eklendi!", "başlığa gitmek için <a href='index.php?topic_id=$topic_id' class='alert-link'>buraya tıklayın</a> veya entrynize göz gezdirin.", "success", "index.php?entry_id=$entry_id");
            else
                Template::show_warning("entry eklerken hata!", "girdiğiniz bilgileri kontrol edin ve saçma karakterler kullanmadığınıza emin olun.", "danger", $_SERVER['HTTP_REFERER']);
        }
    }
	else if($operation == "remove") {
		$entry_id = $_GET['entry_id'];
		$entry = Entry::get_entry($entry_id);
		
		if(Member::check_permission($entry['member_id'])) {
			if(Entry::remove($entry_id))
				Template::show_warning("entry silindi!", "entry tamamen silindi.", $_SERVER['HTTP_REFERER']);
			else
				Template::show_warning("entry silinemedi!", "silmeyi başaramadık, bi sıkıntı var.", "danger", $_SERVER['HTTP_REFERER']);
		}
		else {
			Template::show_warning("entry silinemedi!", "buna yetkiniz yok gibi görünüyor, emin misiniz?", "danger", $_SERVER['HTTP_REFERER']);
		}
	}
	else if($operation == "edit") {
		$entry_id = $_POST['entry_id'];
		$entry_content = $_POST['entry_content'];
		if(Member::check_permission(Entry::get_entry($entry_id)['member_id'])) {
			if(Entry::edit($entry_id, $entry_content))
				Template::show_warning("entry düzenlendi!", "entrynin düzenlenmiş halini aşağıda görebilirsiniz.", "info", "index.php?entry_id=$entry_id");
			else
				Template::show_warning("hata!", "entry düzenlenirken bir şeyler oldu ve düzenlenemedi.", "danger", "index.php?entry_id=$entry_id");
		}
		else
			Template::show_warning("düzenlenirken hata!", "buna yetkiniz yok gibi görünüyor, emin misiniz?.", "danger", "index.php?entry_id=$entry_id");
	}
    else if ($operation == "add_reply") {
        if (Member::is_signed() && isset($_POST['add_reply'])) {
            $entry_content = $_POST['entry_content'];
            $entry_id = $_POST['entry_id'];
            
            $reply_id = Entry::add_reply(Member::get_signed_member_id(), $entry_id, $entry_content);
            if($reply_id)
				Template::show_warning("cevap verildi!", "cevap verdiğiniz entryi görmek için <a href='index.php?entry_id=$entry_id' class='alert-link'>buraya tıklayın</a> veya cevabınıza göz gezdirin.", "success", "index.php?entry_id=$reply_id");
            else
                Template::show_warning("cevap eklerken hata!", "girdiğiniz bilgileri kontrol edin ve saçma karakterler kullanmadığınıza emin olun.", "danger", $_SERVER['HTTP_REFERER']);
        }
    }
    else if ($operation == "get_replies") {
        $entry_id = $_GET['entry_id'];
        $replies = array();
        
        foreach(Entry::get_replies($entry_id) as $entry) {
            $member = Member::get_member($entry['member_id']);
            include("html/single_entry.php");
        }
        if (Member::is_signed()) { ?>
		<div class="form-group">
			<form action="<?php echo Template::form_action_add_reply(); ?>" method="post">
				<input type="hidden" name="entry_id" value="<?php echo $entry_id ?>" />
				<label for="entry_content">cevap ver:</label>
				<textarea class="form-control" rows="5" id="entry_content" name="entry_content"></textarea>
				<button name="add_reply">gönder</button>
			</form>
		</div>
        <?php }
    }
}
else if (isset($_POST['entry_vote']) && Member::is_signed()) {
    $entry_vote = $_POST['entry_vote'];
    $entry_id = $_POST['entry_id'];

    if ($entry_vote == "up")
        PhpOperations::print_boolean(Member::vote_entry(Member::get_signed_member_id(), $entry_id, Vote::UP));
    else
        PhpOperations::print_boolean(Member::vote_entry(Member::get_signed_member_id(), $entry_id, Vote::DOWN));
}

class Vote {
    const UP = 'u';
    const DOWN = 'd';
}

class ValidateEntry {
    public static function content($name, $lenmin = 2, $lenmax = 5000) {
        return ValidateOperations::only_alphanum_space($name, $lenmin, $lenmax);
    }
}

class Entry {
    public static function edit($entry_id, $entry_content) {
		$db = Connection::get_instance()->db;
		$query = sprintf("UPDATE entry SET entry_content='%s' WHERE entry_id=%d",
						 $db->real_escape_string($entry_content), $db->real_escape_string($entry_id));
		
		if($db->query($query))
			return true;
		return false;
    }
	
    public static function add_topic($member_id, $topic_id, $entry_content) {
        $member_id = trim($member_id);
        $entry_content = trim($entry_content);
        $topic_id = trim($topic_id);
        
        if(!ValidateEntry::content($entry_content))
            return false;
        
        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO entry (member_id, entry_content) value ('%s','%s')",
                        $db->real_escape_string($member_id), $db->real_escape_string($entry_content));
        
        if (!$db->query($sql))
            return false;
        
        $entry_id = $db->insert_id;
        $sql = sprintf("INSERT INTO entry_topic (entry_id, topic_id) value ('%s','%s')",
                        $db->real_escape_string($entry_id), $db->real_escape_string($topic_id));
        
        if(!$db->query($sql))
            return false;
        
        return $entry_id;
    }
    
    public static function add_reply($member_id, $entry_id, $entry_content) {
        $member_id = trim($member_id);
        $entry_content = trim($entry_content);
        $entry_id = trim($entry_id);
        
        if(!ValidateEntry::content($entry_content))
            return false;
        
        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO entry (member_id, entry_content) value ('%s','%s')",
                        $db->real_escape_string($member_id), $db->real_escape_string($entry_content));
        
        if (!$db->query($sql))
            return false;
        
        $reply_id = $db->insert_id;
        $sql = sprintf("INSERT INTO entry_reply (entry_id, reply_id) value ('%s','%s')",
                        $db->real_escape_string($entry_id), $db->real_escape_string($reply_id));
        
        if(!$db->query($sql))
            return false;
        
        return $reply_id;
    }
    
    public static function remove($entry_id) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("DELETE FROM entry WHERE entry_id=%d", $db->real_escape_string($entry_id));
        
        if($db->query($sql)) {
			$sql = sprintf("DELETE FROM entry_reply WHERE entry_id=%d", $db->real_escape_string($entry_id));
			$db->query($sql);
			
			$sql = sprintf("DELETE FROM entry_topic WHERE entry_id=%d", $db->real_escape_string($entry_id));
			$db->query($sql);
			
            return true;
        }
        return false;
    }
    
    public static function get_entry($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM entry WHERE entry_id = %d LIMIT 1",
                        $db->real_escape_string($entry_id));

        $result = $db->query($query);
        $row = $result->fetch_array();
     
        return $row;
    }
    
    public static function get_replies($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM entry_reply WHERE entry_id = %d",
                        $db->real_escape_string($entry_id));

        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield self::get_entry($row['reply_id']);
    }
	
    public static function get_reply_count($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT entry_id FROM entry_reply WHERE entry_id = %d",
                        $db->real_escape_string($entry_id));

        return $db->query($query)->num_rows;
    }
    
    public static function get_topic($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT topic_id FROM entry_topic WHERE entry_id = %d LIMIT 1",
                        $db->real_escape_string($entry_id));

        $result = $db->query($query);
        $row = $result->fetch_array();
        $topic_id = $row['topic_id'];
        
        return Topic::get_topic($topic_id);
    }
    
    public static function search($pattern) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM entry WHERE entry_content LIKE '%%%s%%'", $db->real_escape_string($pattern));
        
        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function upvote($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("UPDATE entry SET entry_up_vote = entry_up_vote + 1 WHERE entry_id = %d",
                        $db->real_escape_string($entry_id));
     
        if (!$db->query($query))
            return false;

        return true;
    }
    
    public static function downvote($entry_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("UPDATE entry SET entry_down_vote = entry_down_vote + 1 WHERE entry_id = %d",
                        $db->real_escape_string($entry_id));
     
        if (!$db->query($query))
            return false;

        return true;
    }
}
?>
