<?php

require __DIR__ . '/../vendor/autoload.php';

use stojg\crop\CropEntropy;

/**
 * @param $image
 * @param $thumbnailspath
 * @param array $options
 * @return string
 * @throws Error
 */
function createThumbnails($image, $thumbnailspath, $options = ["width" => 100, "height" => 100]) {
    // create thumbnail based on entropy in the image
    $center = new CropEntropy($image);
    $croppedImage = $center->resizeAndCrop($options["width"], $options["height"]);
//    $thumbnail = $thumbnailspath . $name . ".png";
    $thumbnail = $thumbnailspath . basename($image);
    $failure = !$croppedImage->writeimage($thumbnail);

    if ($failure) {
        throw new Error('There was a problem cropping the specified image.');
    }

    return $thumbnail;
}
