<?php

namespace YoannBlot\Framework\Utils\Log;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;

/**
 * Class LogTest
 *
 * @package YoannBlot\Framework\Utils\Log
 * @author  Yoann Blot
 */
class LogTest extends TestCase {

    /**
     * Test logger initialization.
     */
    public function testInitialization () {
        static::assertNotNull(LoggerService::get());
    }

    /**
     * Test set log level.
     */
    public function testSetLevel () {
        // default level
        static::assertEquals(LoggerService::DEFAULT_MODE, LoggerService::get()->getLevel());

        // change level to existing
        LoggerService::get()->setLevel('DEBUG');
        static::assertEquals(LogValues::DEBUG, LoggerService::get()->getLevel());
        // lower
        LoggerService::get()->setLevel('info');
        static::assertEquals(LogValues::INFO, LoggerService::get()->getLevel());

        // change level to non existing
        LoggerService::get()->setLevel('FAKE');
        static::assertNotEquals(0, LoggerService::get()->getLevel());
    }

    /**
     * Test to write a log message.
     */
    public function testWriteMessage () {
        $sMessage = 'test write';
        $oLogger = LoggerService::get();

        $oLogger->setLevel('ERROR');
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');

        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        static::assertTrue(unlink($sFileName));
    }

    public function testDebugMessage () {
        $sMessage = 'test debug';
        $oLogger = LoggerService::get();

        $oLogger->setLevel('DEBUG');
        $oLogger->debug($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        static::assertTrue(unlink($sFileName));
    }

    public function testInfoMessage () {
        $sMessage = 'test info';
        $oLogger = LoggerService::get();

        $oLogger->setLevel('INFO');
        $oLogger->info($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        static::assertTrue(unlink($sFileName));
    }

    public function testWarningMessage () {
        $sMessage = 'test warn';
        $oLogger = LoggerService::get();

        $oLogger->setLevel('WARN');
        $oLogger->warn($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        static::assertTrue(unlink($sFileName));
    }

    public function testErrorMessage () {
        $sMessage = 'test error';
        $oLogger = LoggerService::get();

        $oLogger->setLevel('ERROR');
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        static::assertTrue(unlink($sFileName));
    }

    /**
     * Test allowed messages.
     */
    public function testAllowedMessages () {
        $sMessage = 'test write';
        $oLogger = LoggerService::get();
        $oLogger->setLevel('DEBUG');

        $oLogger->debug($sMessage);
        $oLogger->info($sMessage);
        $oLogger->warn($sMessage);
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        static::assertTrue(false !== strpos($sLogContent, 'DEBUG'));
        static::assertTrue(false !== strpos($sLogContent, 'INFO'));
        static::assertTrue(false !== strpos($sLogContent, 'WARN'));
        static::assertTrue(false !== strpos($sLogContent, 'ERROR'));
        // remove file
        static::assertTrue(unlink($sFileName));
    }
}
