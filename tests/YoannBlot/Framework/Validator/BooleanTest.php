<?php

namespace YoannBlot\Framework\Validator;

use PHPUnit\Framework\TestCase;

/**
 * Class BooleanTest
 *
 * @package YoannBlot\Framework\Validator
 * @author  Yoann Blot
 *
 * @cover   Boolean
 */
class BooleanTest extends TestCase {

    public function testGetValue () {
        static::assertTrue(Boolean::getValue(true));
        static::assertTrue(Boolean::getValue("true"));
        static::assertFalse(Boolean::getValue(false));
        static::assertFalse(Boolean::getValue("false"));
        static::assertTrue(Boolean::getValue(1));
        static::assertFalse(Boolean::getValue(0));
    }

}
