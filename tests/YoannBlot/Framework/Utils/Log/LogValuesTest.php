<?php

namespace YoannBlot\Framework\Utils\Log;

class LogValuesTest extends \PHPUnit_Framework_TestCase {

    public function testGetValid () {
        $this->assertEquals(LogValues::DEBUG, LogValues::get('debug'));
        $this->assertEquals(LogValues::DEBUG, LogValues::get('DEBUG'));

        $this->assertEquals(LogValues::INFO, LogValues::get('info'));
        $this->assertEquals(LogValues::INFO, LogValues::get('INFO'));

        $this->assertEquals(LogValues::WARN, LogValues::get('WARN'));
        $this->assertEquals(LogValues::WARN, LogValues::get('warn'));

        $this->assertEquals(LogValues::ERROR, LogValues::get('error'));
        $this->assertEquals(LogValues::ERROR, LogValues::get('ERROR'));
    }

    public function testGetInvalid () {
        $this->assertEquals(LogValues::NULL, LogValues::get('fail'));
        $this->assertEquals(LogValues::NULL, LogValues::get('wrong log format'));
    }
}
