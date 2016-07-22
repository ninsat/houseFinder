<?php

namespace YoannBlot\Framework\Model\DataBase;

use YoannBlot\Framework\Utils\File\Loader;

/**
 * Class DataBase ConfigurationLoader.
 * Load database configuration.
 *
 * @package YoannBlot\Framework\Model\DataBase
 * @author  Yoann Blot
 */
final class ConfigurationLoader {

    const SECTION = 'DATABASE';
    const HOST = 'host';
    const PORT = 'port';
    const USER = 'username';
    const PASSWORD = 'password';
    const DATABASE_NAME = 'name';

    /**
     * @var DataBaseConfig database configuration.
     */
    private static $oConfig = null;

    /**
     * Load database configuration.
     */
    private static function load () {
        static::$oConfig = new DataBaseConfig();
        static::$oConfig->setHost(Loader::get(static::HOST, static::SECTION));
        static::$oConfig->setPort(Loader::get(static::PORT, static::SECTION));
        static::$oConfig->setUsername(Loader::get(static::USER, static::SECTION));
        static::$oConfig->setPassword(Loader::get(static::PASSWORD, static::SECTION));
        static::$oConfig->setDatabaseName(Loader::get(static::DATABASE_NAME, static::SECTION));
    }

    /**
     * @return DataBaseConfig database configuration
     */
    public static function get (): DataBaseConfig {
        if (null === self::$oConfig) {
            static::load();
        }

        return self::$oConfig;
    }
}