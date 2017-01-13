<?php

require_once "sanitizeName.php";

/**
 * @param $files
 * @param $path
 * @return array
 * @throws Error
 */
function convertFilesToPNG($files, $path) {
    if (!count($files) || $path === '') {
        if (getenv("ENV") === "development") {
            echo "<pre>";
            print_r($files);
            echo "\n";
            print_r($path);
            echo "</pre>";
        }

        throw new Error("No files found from post.");
    }

    $conversionResults = [];
    foreach ($files as $index => $file) {

        // if file upload not ok, move on
        if ($file["error"] != UPLOAD_ERR_OK) {
            // TODO[kyle]: Notify user there was a problem with one or more images.
            array_push($conversionResults, false);
            continue;
        }

        // uploaded file
        $tmp_name = $file["tmp_name"];

        $name = sanitizeName($file["name"], 'file');

        // remove extension to normalize image types to png
        $imageType = pathinfo($name, PATHINFO_EXTENSION);
        $name = str_replace('.' . $imageType, '', $name);

        // make all images png and write to disk
        $image = $path . $name . ".png";
        echo "<div>";
        echo "converting to png ". $image;
        echo "</div>";
        $failure = !imagepng(imagecreatefromstring(file_get_contents($tmp_name)), $image);

        if ($failure) {
            array_push($conversionResults, false);
            throw new Error('There was a problem converting the image to a png.');
        }

        array_push($conversionResults, $image);
    }

    return $conversionResults;
}
