<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set("log_errors", "0");

define("LANGUAGE", "en");
define("CHARSET", "UTF-8");
define("LOCALE", "hu_HU");

define("DEBUG", 0);


define("VALUE", 42);
define("DATA", '{
"5":{
        "0":0,"1":4,"2":15
},
"7":{
        "1":56,"2":31,"3":42
},
"40":{
        "a":21,"b":19,"c":33,"42":{
                "m":22,"n":{
                        "x":13,"y":25,"z":42
                }
        }
},
"99":{
        "1":21,"2":19,"42":33,"39":{
                "g":42,"e":13,"f":25,"h":49
        }
},
"samplekey":42
}');

$data = json_decode(DATA, true);
//var_dump($data);

$count_value = 0;

function search_callback($needle, $key, $value)
{
//    echo $key." => ".$needle."\n";
    if ($needle == $value)
        $GLOBALS["count_value"]++;
}

array_walk_recursive($data, 'search_callback', VALUE);

echo $GLOBALS["count_value"]."\n";

$count_value = 0;

function array_search_recursive($needle, $haystack, $parent=0, $parent_key=0) {
    foreach($haystack as $key => $value) {
//        echo "[".$key." => ".$value.", ".$parent.", ".$parent_key."]\n";
        if ($key == $needle) {
            $parent_key = $parent;
        }
        if (($value == $needle) && ($parent_key) && ($parent_key <= $parent)) {
            $GLOBALS["count_value"]++;
        }
        $current_key = $key;
        if (is_array($value) && (array_search_recursive($needle, $value, $parent+1, $parent_key) !== false)) {
            return $current_key;
        }
    }
    return false;
}

array_search_recursive(VALUE, $data);

echo $GLOBALS["count_value"]."\n";
?>
