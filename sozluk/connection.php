<?php
require_once("config.php");

class Connection {
    private static $instance = NULL;
    public $db = NULL;
    
    public function __construct() {
        $this->db = new mysqli(HOST, DB_USER, DB_PASSWD);

        if ($this->db->connect_error)
            die("Connection failed: " . $this->db->connect_error);
            
        $this->db->select_db(DB_NAME);
        $this->db->set_charset('utf8');
        $this->db->query('SET NAMES UTF8;');
        $this->db->query('SET COLLATION_CONNECTION=utf8_general_ci;');
    }
    
    public static function get_instance() {
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }
}
