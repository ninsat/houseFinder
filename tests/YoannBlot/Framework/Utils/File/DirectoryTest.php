<?php

namespace YoannBlot\Framework\Utils\File;

/**
 * Class DirectoryTest
 *
 * @package YoannBlot\Framework\Utils\File
 */
class DirectoryTest extends \PHPUnit_Framework_TestCase {

    const DIR = TESTS_PATH . 'Resources' . DIRECTORY_SEPARATOR;

    /**
     * Test to create a directory.
     */
    public function testCreate () {
        // wrong directory name
        $this->assertFalse(Directory::create(''));

        if (is_dir(self::DIR)) {
            Directory::delete(self::DIR);
        }
        $this->assertTrue(Directory::create(self::DIR));
        $this->assertTrue(is_dir(self::DIR));

        $this->assertTrue(rmdir(self::DIR));
    }

    /**
     * Test to clean a directory and all its content.
     */
    public function testClean () {
        // clean non existing directory
        $this->assertTrue(Directory::delete(self::DIR));

        $this->assertTrue(Directory::create(self::DIR));
        $iSize = 12;

        // write $iSize files
        for ($iCpt = 1; $iCpt <= $iSize; $iCpt++) {
            file_put_contents(self::DIR . $iCpt . '.txt', $iCpt);
        }

        // clean directory
        $this->assertTrue(Directory::delete(self::DIR));

        // check that directory doesn't exist anymore
        $this->assertFalse(is_dir(self::DIR));
    }
}
