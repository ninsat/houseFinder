<?php

namespace YoannBlot\Framework\Utils\Log;

use PHPUnit\Framework\TestCase;

class LogValuesTest extends TestCase {

    public function testGetValid () {
        static::assertEquals(LogValues::DEBUG, LogValues::get('debug'));
        static::assertEquals(LogValues::DEBUG, LogValues::get('DEBUG'));

        static::assertEquals(LogValues::INFO, LogValues::get('info'));
        static::assertEquals(LogValues::INFO, LogValues::get('INFO'));

        static::assertEquals(LogValues::WARNING, LogValues::get('WARN'));
        static::assertEquals(LogValues::WARNING, LogValues::get('warn'));

        static::assertEquals(LogValues::ERROR, LogValues::get('error'));
        static::assertEquals(LogValues::ERROR, LogValues::get('ERROR'));
    }

    public function testGetInvalid () {
        static::assertEquals(LogValues::NULL, LogValues::get('fail'));
        static::assertEquals(LogValues::NULL, LogValues::get('wrong log format'));
    }
}
