<?php

/**
 * Similar to imgur naming convention
 * @param int $length
 * @return string
 */
function unique($length = 5) {

    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $string = "";

    for ($i = 0; $i < $length; $i++) {
        $string .= $chars[mt_rand(0, strlen($chars) - 1)];
    }

    return $string;
}
