<?php

define('ROOT_PATH', __DIR__ . DIRECTORY_SEPARATOR);
define('SRC_PATH', ROOT_PATH . 'src' . DIRECTORY_SEPARATOR);

/**
 * Autoload class from its name.
 *
 * @param string $sClassName
 *            class name.
 */
function autoloadClass ($sClassName) {
    $sFilePath = __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $sClassName . '.php';
    $sFilePath = str_replace('\\', '/', $sFilePath);
    if (is_file($sFilePath)) {
        /** @noinspection PhpIncludeInspection */
        require_once $sFilePath;
    }
}

spl_autoload_register('autoloadClass');