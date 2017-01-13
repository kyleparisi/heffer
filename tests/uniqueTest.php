<?php

use \PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../src/unique.php";

class uniqueTest extends TestCase {
    public function testWillNotBeEmpty() {
        $this->assertNotEmpty(unique());
    }
    public function testWillReturnSpecifiedLengthOfCharacters() {
        $this->assertEquals(strlen(unique(10)), 10);
    }
}
