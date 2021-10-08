<?php
error_reporting(E_ALL);
ini_set("display_errors", "1");
ini_set("display_startup_errors", "1");
ini_set("log_errors", "0");

define("DEBUG", 0);


define("VALUE_TO_FIND", 42);
define("INPUT_DATA", '{
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
define("INPUT_DATA_2", '{
"5":{
        "0":0,"1":4,"2":15
},
"7":{
        "1":56,"2":31,"3":42
},
"42":{
        "a":21,"b":19,"c":33,"49":{
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

$input_data = json_decode(INPUT_DATA, true);
if (DEBUG) var_dump($input_data);

$value_count = 0;

function array_walk_callback($value, $key, $value_to_find)
{
    if (DEBUG) echo $key." => ".$value."\n";
    if ($value == $value_to_find)
        $GLOBALS["value_count"]++;
}

array_walk_recursive($input_data, 'array_walk_callback', VALUE_TO_FIND);

echo $GLOBALS["value_count"]."\n";

$value_count = 0;

function array_search_recursive($input_data, $value_to_find, $value_level=0, $key_found=false) {
    foreach($input_data as $key => $value) {
        if (DEBUG) echo "[".$key." => ".(is_array($value) ? "Array" : $value).", ".$value_level.", {".$key_found."}]\n";
        if ($value_level == 0)
            $key_found = false;
        if ($key == $value_to_find) {
            $key_found = true;
        }
        if ((!is_array($value)) && ($value == $value_to_find) && ($key_found)) {
            $GLOBALS["value_count"]++;
        }
        $current_key = $key;
        if (is_array($value) && (array_search_recursive($value, $value_to_find, $value_level+1, $key_found) !== false)) {
            return $current_key;
        }
    }
    return false;
}

array_search_recursive($input_data, VALUE_TO_FIND);

echo $GLOBALS["value_count"]."\n";

?>
