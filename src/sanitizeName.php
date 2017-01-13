<?php

define('FILE', "/[^a-zA-Z0-9_ \.-]/");
define('FOLDER', "/[^a-zA-Z0-9_ -]/");

/**
 * @param $name
 * @param string $type
 * @return mixed|string
 */
function sanitizeName($name, $type = 'file') {

    $name = basename($name);

    switch ($type) {
        case 'file':
            $name = preg_replace(FILE, "", $name);
            break;
        case 'folder':
            $name = preg_replace(FOLDER, "", $name);
            break;
        default:
            $name = preg_replace(FILE, "", $name);
            break;
    }

    return $name;
}
