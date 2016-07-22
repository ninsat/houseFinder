<?php

namespace Framework\Model\DataBase;

use YoannBlot\Framework\Model\DataBase\DataBaseConfig;

/**
 * Class DataBaseConfigTest
 *
 * @package Framework\Model\DataBase
 * @author  Yoann Blot
 */
class DataBaseConfigTest extends \PHPUnit_Framework_TestCase {

    public function testHost () {
        $oConfig = new DataBaseConfig();
        // default value
        $this->assertEmpty($oConfig->getHost());

        // valid
        $sHost = 'www.fake.host.com';
        $oConfig->setHost($sHost);
        $this->assertNotNull($oConfig->getHost());
        $this->assertEquals($sHost, $oConfig->getHost());
    }

    public function testPort () {
        $oConfig = new DataBaseConfig();
        // default value
        $this->assertNotNull($oConfig->getPort());
        $this->assertEquals(DataBaseConfig::DEFAULT_PORT, $oConfig->getPort());

        // valid
        $iPort = 5012;
        $oConfig->setPort($iPort);
        $this->assertNotNull($oConfig->getPort());
        $this->assertEquals($iPort, $oConfig->getPort());
    }

    public function testUsername () {
        $oConfig = new DataBaseConfig();
        // default value
        $this->assertEmpty($oConfig->getUsername());

        // valid
        $sUsername = 'yoann';
        $oConfig->setUsername($sUsername);
        $this->assertNotNull($oConfig->getUsername());
        $this->assertEquals($sUsername, $oConfig->getUsername());
    }

    public function testPassword () {
        $oConfig = new DataBaseConfig();
        // default value
        $this->assertEmpty($oConfig->getPassword());

        // valid
        $sPassword = '1secretPassword';
        $oConfig->setPassword($sPassword);
        $this->assertNotNull($oConfig->getPassword());
        $this->assertEquals($sPassword, $oConfig->getPassword());
    }

    public function testDatabaseName () {
        $oConfig = new DataBaseConfig();
        // default value
        $this->assertEmpty($oConfig->getDatabaseName());

        // valid
        $sName = 'db_name';
        $oConfig->setDatabaseName($sName);
        $this->assertNotNull($oConfig->getDatabaseName());
        $this->assertEquals($sName, $oConfig->getDatabaseName());
    }
}
