<?php

use \PHPUnit\Framework\TestCase;

require_once __DIR__ . "/../src/sanitizeName.php";

class sanitizeNameTest extends TestCase {
    public function testWillNotBeEmpty() {
        $this->assertNotEmpty(sanitizeName('test'));
    }
    public function testWillReturnTheBaseName() {
        $this->assertEquals('b', sanitizeName('a/b', 'file'));
        $this->assertEquals('b', sanitizeName('a/b', 'folder'));
    }
    public function testWillReturnRemoveBadCharacters() {
        $this->assertEquals('', sanitizeName('?!@#$%^&*()+', 'file'));
        $this->assertEquals('', sanitizeName('?!@#$%^&*()+', 'folder'));
    }
    public function testWillAllowDotsInFileNames() {
        $this->assertEquals('.', sanitizeName('.', 'file'));
    }
    public function testWillNotAllowDotsInFolderNames() {
        $this->assertEquals('', sanitizeName('.', 'folder'));
    }
}
