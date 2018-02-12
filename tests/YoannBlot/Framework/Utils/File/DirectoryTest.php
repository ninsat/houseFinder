<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Utils\File;

use PHPUnit\Framework\TestCase;

/**
 * Class DirectoryTest
 *
 * @package YoannBlot\Framework\Utils\File
 */
class DirectoryTest extends TestCase {

    const DIR = TESTS_PATH . 'Resources' . DIRECTORY_SEPARATOR;

    /**
     * Test to create a directory.
     */
    public function testCreate () {
        // wrong directory name
        static::assertFalse(Directory::create(''));

        if (is_dir(self::DIR)) {
            Directory::delete(self::DIR);
        }
        static::assertTrue(Directory::create(self::DIR));
        static::assertTrue(is_dir(self::DIR));

        static::assertTrue(rmdir(self::DIR));
    }

    /**
     * Test to clean a directory and all its content.
     */
    public function testClean () {
        // clean non existing directory
        static::assertTrue(Directory::delete(self::DIR));

        static::assertTrue(Directory::create(self::DIR));
        $iSize = 12;

        // write $iSize files
        for ($iCpt = 1; $iCpt <= $iSize; $iCpt++) {
            file_put_contents(self::DIR . $iCpt . '.txt', $iCpt);
        }

        // clean directory
        static::assertTrue(Directory::delete(self::DIR));

        // check that directory doesn't exist anymore
        static::assertFalse(is_dir(self::DIR));
    }
}
