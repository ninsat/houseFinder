<?php
declare(strict_types=1);

namespace Framework\Model\DataBase;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Model\DataBase\DataBaseConfig;

/**
 * Class DataBaseConfigTest
 *
 * @package Framework\Model\DataBase
 * @author  Yoann Blot
 */
class DataBaseConfigTest extends TestCase {

    public function testHost () {
        $oConfig = new DataBaseConfig();
        // default value
        static::assertEmpty($oConfig->getHost());

        // valid
        $sHost = 'www.fake.host.com';
        $oConfig->setHost($sHost);
        static::assertNotNull($oConfig->getHost());
        static::assertEquals($sHost, $oConfig->getHost());
    }

    public function testPort () {
        $oConfig = new DataBaseConfig();
        // default value
        static::assertNotNull($oConfig->getPort());
        static::assertEquals(DataBaseConfig::DEFAULT_PORT, $oConfig->getPort());

        // valid
        $iPort = 5012;
        $oConfig->setPort($iPort);
        static::assertNotNull($oConfig->getPort());
        static::assertEquals($iPort, $oConfig->getPort());
    }

    public function testUsername () {
        $oConfig = new DataBaseConfig();
        // default value
        static::assertEmpty($oConfig->getUsername());

        // valid
        $sUsername = 'yoann';
        $oConfig->setUsername($sUsername);
        static::assertNotNull($oConfig->getUsername());
        static::assertEquals($sUsername, $oConfig->getUsername());
    }

    public function testPassword () {
        $oConfig = new DataBaseConfig();
        // default value
        static::assertEmpty($oConfig->getPassword());

        // valid
        $sPassword = '1secretPassword';
        $oConfig->setPassword($sPassword);
        static::assertNotNull($oConfig->getPassword());
        static::assertEquals($sPassword, $oConfig->getPassword());
    }

    public function testDatabaseName () {
        $oConfig = new DataBaseConfig();
        // default value
        static::assertEmpty($oConfig->getDatabaseName());

        // valid
        $sName = 'db_name';
        $oConfig->setDatabaseName($sName);
        static::assertNotNull($oConfig->getDatabaseName());
        static::assertEquals($sName, $oConfig->getDatabaseName());
    }
}
