<html>
<head>

    <title>Upload collections of pictures</title>
    <meta name="description=" content="Upload your collections of images.  Images are converted to PNG and also entropy cropped for thumbnails">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont,
            "Segoe UI", "Roboto", "Oxygen",
            "Ubuntu", "Cantarell", "Fira Sans",
            "Droid Sans", "Helvetica Neue", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            background-color: #fdf6e3;

            background-image: -moz-radial-gradient(45px 45px 45deg, circle cover, #90a7c1 0%, #d8e4f3 100%);
            background-image: -webkit-radial-gradient(45px 45px, circle cover, #90a7c1, #d8e4f3);
            background-image: radial-gradient(45px 45px 45deg, circle cover, #90a7c1 0%, #d8e4f3 100%);
        }

        .container {
            width: 66%;
            margin: auto;
        }

        input::-webkit-input-placeholder { /* Chrome/Opera/Safari */
            color: black;
        }

    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
</head>
<body>
<div class="container">

<?php

require 'vendor/autoload.php';
require __DIR__ . '/src/getPreSignedRequests.php';
require __DIR__ . '/src/unique.php';
require __DIR__ . '/src/reArrayFiles.php';
require __DIR__ . '/src/sanitizeName.php';
require __DIR__ . '/src/convertFilesToPNG.php';
require __DIR__ . '/src/createThumbnails.php';

// example url:
// https://s3.amazonaws.com/heffer.ituls.com/uploads/Uefob/full/147196602043115533130333895752868975607808n.png
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'profile' => 'personal',
    'region'  => 'us-east-1'
]);

if (getenv('ENV') === 'production') {

    // Router to collections
    $uri = $_SERVER['REQUEST_URI'];
    $route = explode('/', $uri);
    if ($route[1] === 'uploads') {

        $urls = getPreSignedRequests($s3, $route);

        foreach ($urls as $url) {
            if (strpos($url, "thumbnails")) {
                echo sprintf('<img src="%s">', $url);
            }
        }

    }
}

if (getenv('ENV') === 'development') {

    // Router to collections
    $uri = $_SERVER['REQUEST_URI'];
    $route = explode('/', $uri);
    if ($route[1] === 'uploads') {

        include_once __DIR__ . urldecode($uri) . "index.php";

    }

}

if ($_POST && isset($_FILES['files'])) {

    $collectionName = sanitizeName($_POST['name'], 'folder');
    $unique = unique();
    $files = reArrayFiles($_FILES['files']);

    // make upload directories for normalizations
    $path = sprintf("./%s/%s/%s/", "uploads", $unique, $collectionName);
    $thumbnailspath = sprintf("./%s/%s/%s/thumbnails/", "uploads", $unique, $collectionName);
    if (!mkdir($path, 0755, true) || !mkdir($thumbnailspath, 0755, true)) {
        throw new Error('Problem making collection directory');
    }

    // process each file
    $results = convertFilesToPNG($files, $path);
    foreach ($results as $image) {
        $thumbnail = createThumbnails($image, $thumbnailspath);

        if (getenv("ENV") === "production") {

            echo "<div>".str_replace("./", "/", $path) . basename($image)."</div>";
            $result = $s3->putObject([
                'Bucket'        => 'heffer.ituls.com',
                'Key'           => str_replace("./", "", $path) . basename($image),
                'SourceFile'    => $image
            ]);

            echo "<div>".str_replace("./", "/", $thumbnailspath) . basename($thumbnail)."</div>";
            $result = $s3->putObject([
                'Bucket'        => 'heffer.ituls.com',
                'Key'           => str_replace("./", "", $thumbnailspath) . basename($thumbnail),
                'SourceFile'    => $thumbnail
            ]);

            // Remove state from upload
            unlink($image);
            unlink($thumbnail);

        }

    }

    if (getenv("ENV") === "development") {
        copy('./template/collection.php', $path . 'index.php');
    }

    if (getenv("ENV") === "production") {
        rmdir($thumbnailspath);
        rmdir($path);
        rmdir(sprintf("./%s/%s/", "uploads", $unique));
    }

    echo "<a href='http://" . $_SERVER['HTTP_HOST'] . str_replace('./', '/', $path) . "'>New collection saved!</a>";
}


?>

    <h1 style="text-align: center   ">H E F F E R</h1>
    <form method="POST" enctype="multipart/form-data">
        <div class="file-field input-field">
            <div class="btn">
                <span>Files</span>
                <input type="file" name="files[]" multiple>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload one or more files">
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input id="name" name="name" type="text">
                <label for="name" style="color: black">Collection Name</label>
            </div>
        </div>

        <button class="btn waves-effect waves-light" type="submit" name="action">Submit
            <i class="material-icons left">cloud</i>
        </button>
    </form>


</div>

</body>
</html>




