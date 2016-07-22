<?php

namespace Framework\Model\DataBase;

use YoannBlot\Framework\Model\DataBase\ConfigurationLoader;

/**
 * Class ConfigurationLoaderTest
 *
 * @package Framework\Model\DataBase
 */
class ConfigurationLoaderTest extends \PHPUnit_Framework_TestCase {

    public function testLoad () {
        ConfigurationLoader::get();
    }

    public function testGet () {
        $oConfig = ConfigurationLoader::get();
        $this->assertNotNull($oConfig);
        $this->assertNotNull($oConfig->getHost());
        $this->assertNotNull($oConfig->getPort());
        $this->assertNotNull($oConfig->getUsername());
        $this->assertNotNull($oConfig->getPassword());
    }
}
