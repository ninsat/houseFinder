<?php

namespace YoannBlot\Framework\Model\DataBase;

/**
 * Class DataBaseConfig
 *
 * @package YoannBlot\Framework\Model\DataBase
 * @author  Yoann Blot
 */
final class DataBaseConfig {

    const DEFAULT_PORT = 3306;

    /**
     * @var string host name.
     */
    private $sHost = '';

    /**
     * @var int port number.
     */
    private $iPort = self::DEFAULT_PORT;

    /**
     * @var string user name.
     */
    private $sUsername = '';

    /**
     * @var string password
     */
    private $sPassword = '';

    /**
     * @var string database name
     */
    private $sDatabaseName = '';

    /**
     * @return string
     */
    public function getHost (): string {
        return $this->sHost;
    }

    /**
     * @param string $sHost
     */
    public function setHost (string $sHost) {
        $this->sHost = $sHost;
    }

    /**
     * @return int
     */
    public function getPort (): int {
        return $this->iPort;
    }

    /**
     * @param int $iPort
     */
    public function setPort (int $iPort) {
        $this->iPort = $iPort;
    }

    /**
     * @return string
     */
    public function getUsername (): string {
        return $this->sUsername;
    }

    /**
     * @param string $sUsername
     */
    public function setUsername (string $sUsername) {
        $this->sUsername = $sUsername;
    }

    /**
     * @return string
     */
    public function getPassword (): string {
        return $this->sPassword;
    }

    /**
     * @param string $sPassword
     */
    public function setPassword (string $sPassword) {
        $this->sPassword = $sPassword;
    }

    /**
     * @return string
     */
    public function getDatabaseName (): string {
        return $this->sDatabaseName;
    }

    /**
     * @param string $sDatabaseName
     */
    public function setDatabaseName (string $sDatabaseName) {
        $this->sDatabaseName = $sDatabaseName;
    }

}