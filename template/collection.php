<?php

$images = glob("./*.png");

foreach($images as $image) {
    echo '<img src="'.$image.'" /><br />';
}