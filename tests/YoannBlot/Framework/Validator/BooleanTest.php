<?php

namespace YoannBlot\Framework\Validator;

/**
 * Class BooleanTest
 *
 * @package YoannBlot\Framework\Validator
 * @author  Yoann Blot
 *
 * @cover   Boolean
 */
class BooleanTest extends \PHPUnit_Framework_TestCase {

    public function testGetValue () {
        $this->assertTrue(Boolean::getValue(true));
        $this->assertTrue(Boolean::getValue("true"));
        $this->assertFalse(Boolean::getValue(false));
        $this->assertFalse(Boolean::getValue("false"));
        $this->assertTrue(Boolean::getValue(1));
        $this->assertFalse(Boolean::getValue(0));
    }

}
