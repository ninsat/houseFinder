<?php

namespace YoannBlot\Framework\Utils\File;

/**
 * Class Configuration Loader
 *
 * @package YoannBlot\Framework\Utils\File
 */
class Loader {

    const FILE = 'default.conf';

    /**
     * Retrieve a configuration parameter.
     *
     * @param string $sParameterName parameter name
     * @param string $sSection       section
     *
     * @return string matched parameter, or an empty string if not found.
     */
    public static function get (string $sParameterName, string $sSection = 'GLOBAL'): string {
        $sParameterValue = '';
        $sConfigFile = CONFIG_PATH . static::FILE;
        if (is_file($sConfigFile)) {
            $aIniParameters = parse_ini_file($sConfigFile, true);
            if (array_key_exists($sSection, $aIniParameters)) {
                $sParameterValue = $aIniParameters[ $sSection ][ $sParameterName ];
            }
        }

        return $sParameterValue;
    }
}