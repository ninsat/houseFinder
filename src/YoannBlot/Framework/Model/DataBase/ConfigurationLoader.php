<?php

namespace YoannBlot\Framework\Model\DataBase;

/**
 * Class DataBase ConfigurationLoader.
 * Load database configuration.
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
final class ConfigurationLoader {

    const FILE = 'database.conf';

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
        $sConfigFile = CONFIG_PATH . static::FILE;
        if (is_file($sConfigFile)) {
            $aIniParameters = parse_ini_file($sConfigFile, true);
            if (array_key_exists(static::SECTION, $aIniParameters)) {
                $aIniParameters = $aIniParameters[ static::SECTION ];
                static::$oConfig = new DataBaseConfig();
                static::$oConfig->setHost($aIniParameters[ static::HOST ]);
                static::$oConfig->setPort($aIniParameters[ static::PORT ]);
                static::$oConfig->setUsername($aIniParameters[ static::USER ]);
                static::$oConfig->setPassword($aIniParameters[ static::PASSWORD ]);
                static::$oConfig->setDatabaseName($aIniParameters[ static::DATABASE_NAME ]);
            }
        }
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