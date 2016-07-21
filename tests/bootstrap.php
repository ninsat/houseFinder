<?php

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

/**
 * Autoload class from its name.
 *
 * @param string $sClassName
 *            class name.
 */
function autoloadTestClass ($sClassName) {
    $sFilePath = __DIR__ . DIRECTORY_SEPARATOR . $sClassName . '.php';
    $sFilePath = str_replace('\\', '/', $sFilePath);
    if (is_file($sFilePath)) {
        /** @noinspection PhpIncludeInspection */
        require_once $sFilePath;
    }
}

spl_autoload_register('autoloadTestClass');