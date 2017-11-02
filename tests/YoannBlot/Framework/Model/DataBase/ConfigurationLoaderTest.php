<?php

namespace Framework\Model\DataBase;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Model\DataBase\ConfigurationLoader;
use YoannBlot\Framework\Model\DataBase\DataBaseConfig;

/**
 * Class ConfigurationLoaderTest
 *
 * @package Framework\Model\DataBase
 * @author  Yoann Blot
 */
class ConfigurationLoaderTest extends TestCase {

    public function testLoad () {
        $oConfiguration = ConfigurationLoader::get();
        static::assertNotNull($oConfiguration);
        static::assertInstanceOf(DataBaseConfig::class, $oConfiguration);
    }

    public function testGet () {
        $oConfig = ConfigurationLoader::get();
        static::assertNotNull($oConfig);
        static::assertNotNull($oConfig->getHost());
        static::assertNotNull($oConfig->getPort());
        static::assertNotNull($oConfig->getUsername());
        static::assertNotNull($oConfig->getPassword());
    }
}
