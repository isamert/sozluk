<?php
require_once("connection.php");

if(isset($_GET['operation'])) {
    $operation = $_GET['operation'];
    
    if($operation == "search") {
        $query = $_GET['query'];
        foreach(Category::search($query) as $category) {
            echo '<a href="#" class="btn btn-sm btn-info set-category" cat_id=' . $category['cat_id'] . '>' . $category['cat_name'] . '</a> ';
        }
    }
}

class ValidateCategory {
    public static function name($name, $lenmin = 2, $lenmax = 64) {
        return ValidateOperations::only_alphanum_space($name, $lenmin, $lenmax);
    }
    
    public static function description($description, $lenmin = 2, $lenmax = 64) {
        return ValidateOperations::only_alphanum_space($description, $lenmin, $lenmax);
    }
}

class Category {
    public static function add($cat_name, $cat_description) {
        $cat_name = trim($cat_name);
        $cat_description = trim($cat_description);
        
        if(!ValidateCategory::name($cat_name) || !ValidateCategory::description($cat_description))
            return false;
        
        if(Category::exists($cat_name))
            return false;
        
        $db = Connection::get_instance()->db;
        $sql = sprintf("INSERT INTO category (cat_name, cat_description) value ('%s','%s')",
                        $db->real_escape_string($cat_name), $db->real_escape_string($cat_description));
        
        if (!$db->query($sql))
            return false;
        
        $id = $db->insert_id;
        return $id;
    }
    
    public static function remove($cat_id) {
        $db = Connection::get_instance()->db;
        $sql = sprintf("DELETE FROM category WHERE cat_id=%s", $db->real_escape_string($cat_id));
        
        if($db->query($sql))
            return true;
        return false;
    }
    
    public static function exists($cat_name) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT cat_id FROM category WHERE cat_name = '%s' LIMIT 1",
                            $db->real_escape_string(trim($cat_name)));
     
        $result = $db->query($query);
        if ($result->num_rows > 0)
            return true;
        
        return false;
    }
    
    public static function search($pattern) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM category WHERE cat_name LIKE '%%%s%%'", $db->real_escape_string($pattern));
        
        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row;
    }
    
    public static function get_category($cat_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM category WHERE cat_id = %d LIMIT 1",
                        $db->real_escape_string($cat_id));

        $result = $db->query($query);
        $row = $result->fetch_array();
     
        return $row;
    }

    public static function get_topics($cat_id) {
        $db = Connection::get_instance()->db;
        $query = sprintf("SELECT * FROM topic WHERE cat_id = %d ORDER BY topic_date DESC",
                        $db->real_escape_string($cat_id));

        $result = $db->query($query);
        while($row = $result->fetch_assoc())
            yield $row;
    }
}
?>