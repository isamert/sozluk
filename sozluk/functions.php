<?php
require_once("config.php");

if(isset($_GET['set_theme'])) {
    $_SESSION['css'] = $_GET['set_theme'];
}

class PhpOperations {
    public static function print_boolean($bool) {
        echo $bool ? "true":"false";
    }
    
    public static function redirect($url) {
        echo "<script>window.location.replace('$url');</script>";
    }
}

class ValidateOperations {
    public static function validate($regex, $str, $lenmin = 2, $lenmax = 64) { //FIXME: tr chars
        $str = trim($str);
        if (empty($str) || strlen($str) > $lenmax || strlen($str) < $lenmin)
            return false;
     
        $result = preg_match($regex, $str);
        if (!$result)
            return false;
     
        return true;
    }
    
    public static function only_alphanum($str, $lenmin = 2, $lenmax = 64) {
        return self::validate("/[\w\p{P}]+/u", $str, $lenmin, $lenmax);
    }
    
    public static function only_alphanum_space($str, $lenmin = 2, $lenmax = 64) {
        return self::validate("/[\w\p{P} ]+/u", $str, $lenmin, $lenmax);
    }
}

class StringOperations {
    public static function starts_with($haystack, $needle) {
         $length = strlen($needle);
         return (substr($haystack, 0, $length) === $needle);
    }
    
    public static function ends_with($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0)
            return true;
        return (substr($haystack, -$length) === $needle);
    }
}


class PathOperations {
    public static function join() {
        $path = "";
        $path_count = func_num_args();
        
        if($path_count < 2)
            return -1;
        
        for ($i = 0; $i < $path_count; $i++) {
            $curr_path = func_get_arg($i);
            $path = $path . $curr_path;
            
            if(!StringOperations::ends_with($curr_path, '/'))
                $path = $path . '/';
        }
        
        $path = rtrim($path, '/');
        return $path;
    }
}

class Template {
    public static function get_css() {
        if(isset($_SESSION['css']))
            return $_SESSION['css'];
        else
            return TEMPLATE;
    }
    
    public static function html_index($message = "Please sign in") {
        return PathOperations::join(self::get_template_root(), "index.php");
    }
    
    public static function html_signin($message = "Please sign in") {
        return PathOperations::join(self::get_template_root(), "signin.php");
    }
    
   public static function html_signup($message = "Please fill the form") {
        return PathOperations::join(self::get_template_root(), "signup.php");
    }
    
    public static function form_action_signup() {
        return PathOperations::join(SITE_ADDRESS, "member.php?operation=signup");
    }
    
    public static function form_action_signin() {
        return PathOperations::join(SITE_ADDRESS, "member.php?operation=signin");
    }
    
    public static function form_action_search() {
        return PathOperations::join(SITE_ADDRESS, "member/signin.php");
    }
    
    public static function form_action_add_entry() {
        return PathOperations::join(SITE_ADDRESS, "entry.php?operation=add");
    }
    
    public static function form_action_edit_entry() {
        return PathOperations::join(SITE_ADDRESS, "entry.php?operation=edit");
    }
    
    public static function form_action_add_reply() {
        return PathOperations::join(SITE_ADDRESS, "entry.php?operation=add_reply");
    }

    public static function form_action_add_topic() {
        return PathOperations::join(SITE_ADDRESS, "topic.php?operation=add");
    }
    
    public static function show_warning($title, $content, $type = 'info', $default_url = '') {
        $url = "warning=yes&title=" . urlencode($title) . "&content=" . urlencode($content) . "&type=" . urlencode($type);
        if($default_url == '') {
            $url = "index.php?" . $url;
            header('Location: ' . PathOperations::join(SITE_ADDRESS, $url));
        }
        else {
            $url = $default_url . "&" . $url;
            header('Location: ' . $url);
        }
    }
}

class TextManipulation {
    public static function entry($content) {
        $find = array( 
            "@\n@", 
            "/\[url\=(.+?)\](.+?)\[\/url\]/is", 
            "/\[b\](.+?)\[\/b\]/is",  
            "/\[i\](.+?)\[\/i\]/is",  
            "/\[u\](.+?)\[\/u\]/is",  
            "/\[img\](.+?)\[\/img\]/is", 
            "/\[email\](.+?)\[\/email\]/is",
            "/\[bkz\](.+?)\[\/bkz\]/is",
            "/\[entry\](.+?)\[\/entry\]/is",
            "/\(bkz:\s?(.+?)\)/is",
            "/\[spoiler\](.+?)\[\/spoiler\]/is",
            "/\[url=(.+?)\](.+?)\[\/url\]/is",
            //"@[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]@is",  
        ); 
        $replace = array(
            "<br />", 
            "<a href=\"$1\" target=\"_blank\">$2</a>", 
            "<strong>$1</strong>", 
            "<em>$1</em>", 
            "<span style=\"text-decoration:underline;\">$1</span>",
            "<img src=\"$1\" alt=\"Image\" />", 
            "<a href=\"mailto:$1\" target=\"_blank\">$1</a>",
            "<a href=\"topic.php?go=$1\">$1</a>" ,
            "<a href=\"index.php?entry_id=$1\">#$1</a>",
            "(bkz: <a href=\"topic.php?go=$1\">$1</a>)" ,
            "-----<span class=\"text-danger\">spoiler</span>-----<br /><br />$1<br /><br />-----<span class=\"text-danger\">spoiler</span>-----",
            "<a href=\"$1\" target=\"blank\">$2</a>",
            //"<a href=\"\\0\">\\0</a>", 
        ); 
        $content = htmlspecialchars($content); 
        $content = preg_replace($find, $replace, $content);

        return mb_strtolower($content);
    }
}

?>






