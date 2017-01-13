<?php

require_once __DIR__ . "/../vendor/autoload.php";

/**
 * @param $s3
 * @param $route
 * @param string $bucket
 * @return array
 */
function getPreSignedRequests($s3, $route, $bucket = 'heffer.ituls.com' ) {

    $collection = $s3->listObjects([
        'Bucket' => $bucket,
        'Prefix' => sprintf('%s/%s/', $route[1], $route[2])
    ]);

    $presignedUrls = [];
    foreach ($collection->toArray()['Contents'] as $object) {

        $cmd = $s3->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key'    => $object['Key']
        ]);


        $url = $s3->createPresignedRequest($cmd, '+5 minutes');
        // Get the actual presigned-url
        $presignedUrl = (string) $url->getUri();

        array_push($presignedUrls, $presignedUrl);

    }

    return $presignedUrls;
}
