<?php require_once("connection.php");

Connection::get_instance()->db->query("INSERT INTO `category` (`cat_name`, `cat_description`) VALUES ('sözlük', 'sözlük ile ilgili her şey.')";
?>
