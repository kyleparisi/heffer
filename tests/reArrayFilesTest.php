<?php

use \PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../src/reArrayFiles.php";

class reArrayFilesTest extends TestCase {
    public function testWillReturnFalseWithEmptyArray() {
        $blankArray = [];
        $this->assertFalse(reArrayFiles($blankArray), "Expecting false when given blank array.");
    }

    public function testWillReturnFalseWhenMissingNameKey() {
        $data = ['test' => []];
        $this->assertFalse(reArrayFiles($data), "Expecting false when given array with missing 'name' key.");
    }

    public function testWillReArrangeArray() {
        $data = [
            "name" => ["14719660_204311553313033_3895752868975607808_n.jpg"],
            "type" => ["image/jpeg"],
            "tmp_name" => ["/tmp/phphQQgjv"],
            "error" => [0],
            "size" => [81827]
        ];
        $output = [
            [
                "name" => "14719660_204311553313033_3895752868975607808_n.jpg",
                "type" => "image/jpeg",
                "tmp_name" => "/tmp/phphQQgjv",
                "error" => 0,
                "size" => 81827
            ]
        ];

        $this->assertEquals(reArrayFiles($data), $output);
    }

    public function testWillReArrangeArrayWith2Files() {
        $data = [
            "name" => ["14719660_204311553313033_3895752868975607808_n.jpg", "test.png"],
            "type" => ["image/jpeg", "image/png"],
            "tmp_name" => ["/tmp/phphQQgjv", "/tmp/pasdfw2"],
            "error" => [0, 0],
            "size" => [81827, 1024]
        ];
        $output = [
            [
                "name" => "14719660_204311553313033_3895752868975607808_n.jpg",
                "type" => "image/jpeg",
                "tmp_name" => "/tmp/phphQQgjv",
                "error" => 0,
                "size" => 81827
            ],
            [
                "name" => "test.png",
                "type" => "image/png",
                "tmp_name" => "/tmp/pasdfw2",
                "error" => 0,
                "size" => 1024
            ]
        ];

        $this->assertEquals(reArrayFiles($data), $output);
    }
}