<?php

/**
 * The files array is split by indexes instead of by file.  This will rearrange it to be
 * an array per file. Reference:
 * Array ( [name] => Array ( [0] => 14719660_204311553313033_3895752868975607808_n.jpg ) [type] => Array ( [0] => image/jpeg ) [tmp_name] => Array ( [0] => /tmp/phphQQgjv ) [error] => Array ( [0] => 0 ) [size] => Array ( [0] => 81827 ) )
 * @param $file_post &array - See above reference
 * @return array|bool
 */
function reArrayFiles(&$file_post) {

    if (!count($file_post) || !isset($file_post['name'])) {
        return false;
    }

    $file_array = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_array[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_array;
}
