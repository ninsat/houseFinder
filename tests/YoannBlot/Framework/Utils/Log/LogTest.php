<?php

namespace YoannBlot\Framework\Utils\Log;

use YoannBlot\Framework\Helper\Reflection;

/**
 * Class LogTest
 *
 * @package YoannBlot\Framework\Utils\Log
 * @author  Yoann Blot
 */
class LogTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test logger initialization.
     */
    public function testInitialization () {
        $this->assertNotNull(Log::get());
    }

    /**
     * Test set log level.
     */
    public function testSetLevel () {
        // default level
        $this->assertEquals(Log::DEFAULT_MODE, Log::get()->getLevel());

        // change level to existing
        Log::get()->setLevel('DEBUG');
        $this->assertEquals(LogValues::DEBUG, Log::get()->getLevel());
        // lower
        Log::get()->setLevel('info');
        $this->assertEquals(LogValues::INFO, Log::get()->getLevel());

        // change level to non existing
        Log::get()->setLevel('FAKE');
        $this->assertNotEquals(0, Log::get()->getLevel());
    }

    /**
     * Test to write a log message.
     */
    public function testWriteMessage () {
        $sMessage = 'test write';
        $oLogger = Log::get();

        $oLogger->setLevel('ERROR');
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');

        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }

    public function testDebugMessage () {
        $sMessage = 'test debug';
        $oLogger = Log::get();

        $oLogger->setLevel('DEBUG');
        $oLogger->debug($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }

    public function testInfoMessage () {
        $sMessage = 'test info';
        $oLogger = Log::get();

        $oLogger->setLevel('INFO');
        $oLogger->info($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }

    public function testWarningMessage () {
        $sMessage = 'test warn';
        $oLogger = Log::get();

        $oLogger->setLevel('WARN');
        $oLogger->warn($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }

    public function testErrorMessage () {
        $sMessage = 'test error';
        $oLogger = Log::get();

        $oLogger->setLevel('ERROR');
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, $sMessage));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }

    /**
     * Test allowed messages.
     */
    public function testAllowedMessages () {
        $sMessage = 'test write';
        $oLogger = Log::get();
        $oLogger->setLevel('DEBUG');

        $oLogger->debug($sMessage);
        $oLogger->info($sMessage);
        $oLogger->warn($sMessage);
        $oLogger->error($sMessage);

        // check created log file
        $sFileName = Reflection::getValue($oLogger, 'getFile');
        $sLogContent = file_get_contents($sFileName);
        // message should be found in log file
        $this->assertTrue(false !== strpos($sLogContent, 'DEBUG'));
        $this->assertTrue(false !== strpos($sLogContent, 'INFO'));
        $this->assertTrue(false !== strpos($sLogContent, 'WARN'));
        $this->assertTrue(false !== strpos($sLogContent, 'ERROR'));
        // remove file
        $this->assertTrue(unlink($sFileName));
    }
}
