<?php
require_once("connection.php");
require_once("functions.php");
require_once("entry.php");

if(isset($_GET['q'])) {
    $query = $_GET['q'];
    $result_array = array();
    
    foreach(Topic::search_all($query) as $topic)
        $result_array[] =  $topic['topic_name'];

    echo json_encode($result_array);
}
elseif(isset($_POST['add_topic']) && Member::is_signed()) {
    $topic_name = $_POST['topic_name'];
    $cat_id = $_POST['cat_id'];
    $entry_content = $_POST['entry_content'];
    
    $topic_id = Topic::add($topic_name, $cat_id);
    if($topic_id) {
        if(Entry::add_topic(Member::get_signed_member_id(), $topic_id, $entry_content)) {
            Template::show_warning("yaşasın!", "başlık başarıyla açıldı.", "success", PathOperations::join(SITE_ADDRESS, "index.php?topic_id=" . $topic_id));
        }
        else {
            Template::show_warning("entry eklerken hata!", "entryi eklerken bazı sıkıntılar oldu, yeniden eklemeyi deneyin.", "danger", PathOperations::join(SITE_ADDRESS, "index.php?topic_id=" . $topic_id));
        }
    }
    else {
        Template::show_warning("başlık eklerken hata!", "kategoriyi doldurmayı unutmayın ve başlık için daha düzgün karakterler kullanmayı deneyin.", "danger", $_SERVER['HTTP_REFERER']);
    }
}
elseif(isset($_GET['go'])) {
    $query = strtolower(htmlspecialchars(urldecode($_GET['go'])));
    $result_array = array();
    
    foreach(Topic::search_all($query) as $topic) {
        if(strtolower(htmlspecialchars($topic['topic_name'])) == $query)
            header('Location: index.php?topic_id=' . $topic['topic_id']);
    }
    
    header('Location: index.php?query=' . $query);
}

class ValidateTopic {
    public static function name($name, $lenmin = 2, $lenmax = 64) {
        return ValidateOperations::only_alphanum_space($name, $lenmin, $lenmax);
    }
}

class Topic {
    public static function add($topic_name, $cat_id) {
        $topic_name = trim($topic_name);
        
        if(!ValidateTopic::name($topic_name))
            return false;
        
        if(Topic::exists($topic_name, $cat_id))
            return false;
        
        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO topic (topic_name, cat_id) value ('%s', %d)",
                        $db->real_escape_string($topic_name), $db->real_escape_string($cat_id));
        
        if (!$db->query($sql))
            return false;
        
        $id = $db->insert_id;
        return $id;
    }
    
    public static function remove($topic_id) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("DELETE FROM topic WHERE topic_id=%s", $db->real_escape_string($topic_id));
        
        if($db->query($sql))
            return true;
        return false;
    }
    
    public static function exists($topic_name, $cat_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT topic_id, cat_id FROM topic WHERE LCASE(topic_name) = '%s' AND cat_id = %d LIMIT 1",
                            $db->real_escape_string(strtolower(trim($topic_name))), $db->real_escape_string($cat_id));
     
        $result = $db->query($query);
        if ($result->num_rows > 0)
            return true;
        
        return false;
    }
    
    public static function search_all($pattern) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM topic WHERE topic_name LIKE '%%%s%%'", $db->real_escape_string(trim($pattern)));
        
        $result = $db->query($query);
        
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function search_with_category($pattern, $cat_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM topic WHERE topic_name LIKE '%%%s%%' AND cat_id = %d",
                         $db->real_escape_string($pattern), $db->real_escape_string($cat_id));
        
        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function upvote($topic_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("UPDATE topic SET topic_upvote = topic_upvote + 1 WHERE topic_id = %d",
                        $db->real_escape_string($topic_id));
     
        if (!$db->query($query))
            return false; //TODO: add error causes

        return true;
    }
    
    public static function downvote($topic_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("UPDATE topic SET topic_downvote = topic_downvote + 1 WHERE topic_id = %d",
                        $db->real_escape_string($topic_id));
     
        if (!$db->query($query))
            return false; //TODO: add error causes

        return true;
    }
    
    public static function get_topic($topic_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM topic WHERE topic_id = %d LIMIT 1",
                        $db->real_escape_string($topic_id));

        $result = $db->query($query);
        $row = $result->fetch_array();
     
        return $row;
    }
    
    public static function get_entries($topic_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT entry_id FROM entry_topic WHERE topic_id = %d",
                        $db->real_escape_string($topic_id));

        $result = $db->query($query);
        while($row = $result->fetch_assoc()) {
            yield Entry::get_entry($row['entry_id']);
        }
    }
    
    public static function get_latest($page = 1, $limit = 15) {
        $start = ($page - 1) * $limit;

        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM topic ORDER BY topic_id DESC LIMIT $start, $limit");

        $result = $db->query($query);
        while($row = $result->fetch_assoc()) {
            yield $row;
        }
    }
    
    public static function get_latest_updated($page = 1, $limit = 15) {
        $start = ($page - 1) * $limit;

        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT topic.topic_id, max(entry_id) as last_entry, topic_name FROM entry_topic INNER JOIN topic ON topic.topic_id = entry_topic.topic_id GROUP BY topic.topic_id ORDER BY last_entry DESC  LIMIT $start, $limit");

        $result = $db->query($query);
        while($row = $result->fetch_assoc()) {
            yield $row;
        }
    }
}
?>