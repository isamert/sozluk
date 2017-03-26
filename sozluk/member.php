<?php
require_once("connection.php");
require_once("functions.php");

if(isset($_GET['operation'])) {
    $operation = $_GET['operation'];
    
    if ($operation == "signin") {
        if(!Member::is_signed() && isset($_POST['signin'])) {
            $member_name = $_POST['member_name'];
            $member_passwd = $_POST['member_passwd'];
            
            if (Member::signin($member_name, $member_passwd))
                header('Location: ' . SITE_ADDRESS);
            else {
                header('Location: ' . SITE_ADDRESS);
            }
        }
        else {
            header('Location: ' . SITE_ADDRESS);
        }
    }
    else if ($operation == "signout") {
        if (session_destroy())
            header("Location: index.php"); 
    }
    else if ($operation == "signup" && isset($_POST['signup'])) {
        $member_name = $_POST['member_name'];
        $member_passwd = $_POST['member_passwd'];
        $member_fullname = $_POST['member_name'];
        $member_mail = $_POST['member_mail'];
        $member_gender = $_POST['member_gender'];
        
        if(Member::add($member_name, $member_passwd, $member_mail, $member_fullname, $member_gender))
            header('Location: ' . PathOperations::join(SITE_ADDRESS, "index.php?login&new_member"));
        else
            Template::show_warning("bir hata oldu!", "bilgilerinizi kontrol edin.", "danger", $_SERVER['HTTP_REFERER']);
    }
    else if ($operation == "signout") {
        if (session_destroy())
            header("Location: index.php");
    }
}
else if (isset($_POST['send_message'])) {
    if(Member::is_signed()) {
        $member_id_sender = Member::get_signed_member_id();
        $member_id_receiver = $_POST['member_id_receiver'];
        $message_content = $_POST['message_content'];
        
        if($member_id_sender != $member_id_receiver)
            PhpOperations::print_boolean(Member::send_message($member_id_sender, $member_id_receiver, $message_content));
        else
            echo "false";
    }
    else
        return false;
}

class ValidateMember {
    public static function mail($mail) {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }
    
    public static function name($name, $lenmin = 2, $lenmax = 64) {
        return ValidateOperations::only_alphanum_space($name, $lenmin, $lenmax);
    }
    
    public static function passwd($passwd, $lenmin = 6, $lenmax = 16) {
        return ValidateOperations::only_alphanum($passwd, $lenmin, $lenmax);
    }
    
    public static function fullname($name, $lenmin = 2, $lenmax = 64) {
        return ValidateOperations::only_alphanum_space($name, $lenmin, $lenmax);
    }
}

class MemberStatus {
    const USER = 'u';
    const MOD = 'm';
}

class Member {
    public static function memberbox() {
        if(self::is_signed())
            include("html/member_box.php");
        else
            include("html/login_form.php");
    }
    
    public static function registerbox() {
        if(!self::is_signed())
            include("html/register_box.php");
        else
            Template::show_warning("bir hata oldu!", "zaten giriş yapmışsınız ki.", "danger", $_SERVER['HTTP_REFERER']);
    }
    
    public static function get_member($member_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM member WHERE member_id = %d LIMIT 1",
                        $db->real_escape_string($member_id));

        $result = $db->query($query);
        $row = $result->fetch_array();
     
        return $row;
    }
    
    public static function exists($member_name) {
        $db = Connection::get_instance()->db;
        
        $query = sprintf("SELECT member_id FROM member WHERE member_name = '%s' LIMIT 1",
                            $db->real_escape_string(trim($member_name)));
     
        $result = $db->query($query);
        if ($result->num_rows > 0)
            return true;
        
        return false;
    }
    
    public static function add($member_name, $member_passwd, $member_mail, $member_fullname, $member_gender) {
        if(!ValidateMember::name($member_name, 2, 64) || !ValidateMember::passwd($member_passwd) || !ValidateMember::mail($member_mail) || !ValidateMember::fullname($member_fullname))
            return false;
        
        if(Member::exists($member_name))
            return false;

        $hashed_passwd = password_hash(trim($member_passwd), PASSWORD_DEFAULT);

        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO member (member_name, member_passwd, member_mail, member_fullname, member_gender) value ('%s','%s','%s','%s','%s')",
                        $db->real_escape_string(trim($member_name)), $hashed_passwd,
                        $db->real_escape_string(trim($member_mail)), $db->real_escape_string(trim($member_fullname)),
                        $db->real_escape_string($member_gender));
        
        if (!$db->query($sql))
            return false;
            
        $id = $db->insert_id;
        return $id;
    }
    
    public static function is_signed() {
        if (isset($_SESSION["member_name"]) && isset($_SESSION["member_id"]))
            return true;
     
        return false;
    }
    
    public static function signin($member_name, $member_passwd) {
        if (!ValidateMember::name($member_name) || !ValidateMember::passwd($member_passwd) || !Member::exists($member_name))
            return false; //TODO: add error causes
     
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT member_id, member_passwd FROM member WHERE member_name = '%s' LIMIT 1;",
                         $db->real_escape_string(trim($member_name)));
        $result = $db->query($query);
        $row = $result->fetch_array();

        if (password_verify($member_passwd, $row['member_passwd'])) {
            $_SESSION['member_id'] = $row['member_id'];
            $_SESSION['member_name'] = $member_name;

            return true;
        } else {
            return false;
        }
    }
    
    public static function change_passwd($member_name, $member_current_passwd, $member_new_passwd) {
        if (!ValidateMember::name($member_name) || !ValidateMember::passwd($member_current_passwd) || !Member::exists($member_name))
            return false; //TODO: add error causes
        if (!ValidateMember::passwd($member_new_passwd) || ($member_current_passwd == $member_new_passwd))
            return false; //TODO: add error causes
     
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT member_passwd FROM member WHERE member_name = '%s' LIMIT 1",
                        $db->real_escape_string($member_name));

        $result = $db->query($query);
        $row = $result->fetch_array();


        if (!password_verify($member_current_passwd, $row['member_passwd'])) // Wrong input
            return false; //TODO: add error causes
     
        
        $query = sprintf("UPDATE member SET member_passwd = '%s' WHERE member_name = '%s'",
                        $db->real_escape_string($member_new_passwd), $db->real_escape_string($member_name));
     
        if (!$db->query($query))
            return false; //TODO: add error causes

        return true;
    }
    
    public static function get_signed_member_id() {
        return $_SESSION["member_id"];
    }
    
    public static function get_signed_member_name() {
        return $_SESSION["member_name"];
    }
    
    public static function is_mod($member_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT member_status FROM member WHERE member_id = %d",
                         $db->real_escape_string($member_id));
        
        $result = $db->query($query);
        if($result) {
            $row = $result->fetch_array();
            if($row['member_status'] == MemberStatus::MOD)
                return true;
        }
        
        return false;
    }

    public static function get_entry_count($member_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT entry_id FROM entry WHERE member_id = %d",
            $db->real_escape_string($member_id));

        $count = $db->query($query)->num_rows;
        return $count;
    }

    public static function get_subbed_categories($member_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT cat_id FROM member_subscribe WHERE member_id = %d",
                        $db->real_escape_string($member_id));

        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row["cat_id"];
    }
    
    public static function get_latest_entries($member_id, $limit = 10) {
        return self::get_all_entries($member_id, 1, $limit);
    }
    
    public static function get_all_entries($member_id, $page = 1, $limit = 15) {
        $start = ($page - 1) * $limit;
        
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM entry INNER JOIN entry_topic ON entry.entry_id=entry_topic.entry_id WHERE member_id = %d ORDER BY entry.entry_id DESC LIMIT $start, $limit",
                        $db->real_escape_string($member_id), $db->real_escape_string($limit));

        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function vote_entry($member_id, $entry_id, $vote) { //FIXME: let member change up to down or vice versa
        $db = Connection::get_instance()->db;
        $sql = sprintf("SELECT member_id FROM member_entry WHERE member_id = %d AND entry_id = %d",
                       $db->real_escape_string($member_id), $db->real_escape_string($entry_id));
        
        if(!($db->query($sql)->num_rows > 0)) {
            $sql = sprintf("INSERT INTO member_entry (member_id, entry_id, vote) value ('%s','%s','%s')",
                            $db->real_escape_string($member_id), $db->real_escape_string($entry_id), $db->real_escape_string($vote));
            
            
            if ($db->query($sql)) {
                $result;
                if($vote == Vote::UP)
                    $result = Entry::upvote($entry_id);
                else
                    $result = Entry::downvote($entry_id);
                return $result;
            }
            
            return false;
        }
        
        return false;
    }
    
    public static function send_message($member_id_sender, $member_id_receiver, $message_content) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO member_message (member_id_sender, member_id_receiver, message_content) value ('%s', '%s', '%s')",
                       $db->real_escape_string($member_id_sender), $db->real_escape_string($member_id_receiver), $db->real_escape_string($message_content));
        
        if (!$db->query($sql))
            return false;
        
        return true;
    }
    
    public static function get_unread_message_count($member_id_receiver) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("SELECT * FROM member_message WHERE member_id_receiver = %d AND message_read = 'f'",
                       $db->real_escape_string($member_id_receiver));
        
        $result = $db->query($sql);
        return $result->num_rows;
    }
    
    public static function get_message_sender_members($member_id_receiver) {
        /* finds members who sent messages to $member_id_receiver */
        $db = Connection::get_instance()->db;
        $sql = sprintf("SELECT member_id, member_name, message_content FROM member_message LEFT JOIN member ON member_message.member_id_sender=member.member_id WHERE member_message.member_id_receiver = %d GROUP BY member_id",
                       $db->real_escape_string($member_id_receiver));
        
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function get_message_receiver_members($member_id_receiver) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("SELECT member_id, member_name, message_content FROM member_message INNER JOIN member ON member_message.member_id_receiver=member.member_id WHERE member_message.member_id_sender = %d GROUP BY member_id",
                       $db->real_escape_string($member_id_receiver));
        
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function get_conversation($member_id_receiver, $member_id_sender) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("SELECT * FROM member_message WHERE (member_id_receiver = %d OR member_id_receiver = %d) AND (member_id_sender = %d OR member_id_sender = %d) ORDER BY message_date",
                       $db->real_escape_string($member_id_receiver), $db->real_escape_string($member_id_sender), $db->real_escape_string($member_id_receiver), $db->real_escape_string($member_id_sender));
        
        $result = $db->query($sql);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function set_messages_all_read($member_id_receiver, $member_id_sender) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("UPDATE member_message SET message_read = 't' WHERE member_id_receiver = %d AND member_id_sender = %d",
                       $db->real_escape_string($member_id_receiver), $db->real_escape_string($member_id_sender));
        
        $result = $db->query($sql);
        if(!$result)
            return false;
        
        return true;
    }
    
    public static function check_permission($member_id) {
        /* checks if member has permission to do things on $member_id's things */
        return (self::is_signed() && ($member_id == self::get_signed_member_id() || self::is_mod(self::get_signed_member_id())));
    }
}
?>
