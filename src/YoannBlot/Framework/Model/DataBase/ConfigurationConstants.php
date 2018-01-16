<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\DataBase;

/**
 * Interface ConfigurationConstants.
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
interface ConfigurationConstants
{
    const PATH = CONFIG_PATH . 'default.conf';

    const SECTION = 'DATABASE';
    const HOST = 'host';
    const PORT = 'port';
    const USER = 'username';
    const PASSWORD = 'password';
    const DATABASE_NAME = 'name';
}