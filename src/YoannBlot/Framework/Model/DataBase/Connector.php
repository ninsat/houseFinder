<?php

namespace YoannBlot\Framework\Model\DataBase;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;

/**
 * Class Connector
 *
 * @package YoannBlot\Framework\Model\DataBase
 */
class Connector {

    /**
     * @var Connector singleton session.
     */
    private static $oCurrent = null;

    /**
     * @return Connector current connector.
     */
    public static function get () : Connector {
        if (null === static::$oCurrent) {
            static::$oCurrent = new Connector();
        }

        return static::$oCurrent;
    }

    /**
     * @var \PDO database connection object.
     */
    private $oConnection = null;

    /**
     * Connector constructor.
     */
    private function __construct () {
        $this->initConnection();
    }

    /**
     * Open a connection to database.
     */
    private function initConnection () {
        if (!$this->isConnected()) {
            $oConfig = ConfigurationLoader::get();
            $sConnectionSettings = 'mysql:host=' . $oConfig->getHost() . ':' . $oConfig->getPort() . ';dbname=' . $oConfig->getDatabaseName();
            $this->oConnection = new \PDO($sConnectionSettings, $oConfig->getUsername(), $oConfig->getPassword());
            $this->oConnection->exec("SET CHARACTER SET utf8");
        }
    }

    /**
     * @return \PDO database connection object.
     */
    private function getConnection () : \PDO {
        return $this->oConnection;
    }

    /**
     * @return bool true if connected to database, otherwise false.
     */
    private function isConnected () : bool {
        return null !== $this->oConnection && $this->oConnection instanceof \PDO;
    }

    /**
     * Close the database connection.
     */
    public function close () {
        if ($this->isConnected()) {
            $this->oConnection = null;
        }
    }

    /**
     * @inheritDoc
     */
    public function __destruct () {
        $this->close();
    }

    /**
     * Query a single object.
     *
     * @param string $sQuery     query to execute
     * @param string $sClassName entity class name
     *
     * @return AbstractEntity matched entity if found, otherwise null.
     * @throws EntityNotFoundException if entity was not found.
     */
    public function querySingle (string $sQuery, string $sClassName): AbstractEntity {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        $oStatement->execute();
        $oObject = $oStatement->fetchObject($sClassName);
        if (false === $oObject) {
            throw new EntityNotFoundException();
        }

        return $oObject;
    }

    /**
     * Query multiple objects.
     *
     * @param string $sQuery     query to execute.
     * @param string $sClassName entity class name
     *
     * @return AbstractEntity[] matched entities as array.
     */
    public function queryMultiple (string $sQuery, string $sClassName): array {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        $oStatement->execute();
        $aObjects = [];
        foreach ($oStatement->fetchAll(\PDO::FETCH_CLASS, $sClassName) as $oObject) {
            $aObjects [] = $oObject;
        }

        return $aObjects;
    }
}