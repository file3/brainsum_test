<?php

define("NUMBER_TO_FIND", 42);

$data = '{
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
}';

function json_pretty_print( $json )
{
	$result = '';
	$level = 0;
	$in_quotes = false;
	$in_escape = false;
	$ends_line_level = NULL;
	$json_length = strlen( $json );

	for( $i = 0; $i < $json_length; $i++ ) {
		$char = $json[$i];
		$new_line_level = NULL;
		$post = "";
		if( $ends_line_level !== NULL ) {
			$new_line_level = $ends_line_level;
			$ends_line_level = NULL;
		}
		if ( $in_escape ) {
			$in_escape = false;
		} else if( $char === '"' ) {
			$in_quotes = !$in_quotes;
		} else if( ! $in_quotes ) {
			switch( $char ) {
				case '}': case ']':
					$level--;
					$ends_line_level = NULL;
					$new_line_level = $level;
					break;

				case '{': case '[':
					$level++;
				case ',':
					$ends_line_level = $level;
					break;

				case ':':
					$post = " ";
					break;

				case " ": case "\t": case "\n": case "\r":
					$char = "";
					$ends_line_level = $new_line_level;
					$new_line_level = NULL;
					break;
			}
		} else if ( $char === '\\' ) {
			$in_escape = true;
		}
		if( $new_line_level !== NULL ) {
			$result .= "\n".str_repeat( "\t", $new_line_level );
		}
		$result .= $char.$post;
	}

	return $result;
}

function recursive_array_search($needle,$haystack) {
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

$arr = json_decode($data, true);

var_dump($arr);

//var_dump(recursive_array_search(NUMBER_TO_FIND, $arr));

$count = 0;

function finder($item, $key)
{
    if ((is_int($item) && ($item === NUMBER_TO_FIND)) || ((is_int($key)) && ($key === NUMBER_TO_FIND)))
        $GLOBALS["count"]++;
}

array_walk_recursive($arr, 'finder');

echo $GLOBALS["count"];
?>
