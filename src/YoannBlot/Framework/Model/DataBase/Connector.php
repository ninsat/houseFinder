<?php

namespace YoannBlot\Framework\Model\DataBase;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;

/**
 * Class Connector
 *
 * @package YoannBlot\Framework\Model\DataBase
 * @author  Yoann Blot
 */
class Connector
{

    /**
     * @var Connector singleton session.
     */
    private static $oCurrent = null;

    /**
     * @return Connector current connector.
     */
    public static function get(): Connector
    {
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
    private function __construct()
    {
        $this->initConnection();
    }

    /**
     * Open a connection to database.
     */
    private function initConnection()
    {
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
    private function getConnection(): \PDO
    {
        return $this->oConnection;
    }

    /**
     * @return bool true if connected to database, otherwise false.
     */
    private function isConnected(): bool
    {
        return null !== $this->oConnection && $this->oConnection instanceof \PDO;
    }

    /**
     * Close the database connection.
     */
    public function close()
    {
        if ($this->isConnected()) {
            $this->oConnection = null;
        }
    }

    /**
     * @inheritDoc
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Execute a query and return default array.
     *
     * @param string $sQuery query to execute
     * @return array data fetched.
     * @throws QueryException query exception.
     */
    public function fetchAll(string $sQuery): array
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute()) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }

        return $oStatement->fetchAll();
    }

    /**
     * Execute a simple query.
     *
     * @param string $sQuery query to execute
     * @return bool true if success, otherwise false.
     */
    public function execute(string $sQuery): bool
    {
        $this->initConnection();
        return false !== $this->getConnection()->exec($sQuery);
    }

    /**
     * Query a single object.
     *
     * @param string $sQuery query to execute
     * @param string $sClassName entity class name
     *
     * @return AbstractEntity matched entity if found, otherwise null.
     * @throws EntityNotFoundException if entity was not found.
     * @throws QueryException query exception.
     */
    public function querySingle(string $sQuery, string $sClassName): AbstractEntity
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute()) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }
        $oObject = $oStatement->fetchObject($sClassName);
        if (false === $oObject) {
            throw new EntityNotFoundException();
        }
        $oObject->addLinks();

        return $oObject;
    }

    /**
     * Query multiple objects.
     *
     * @param string $sQuery query to execute.
     * @param string $sClassName entity class name
     *
     * @return AbstractEntity[] matched entities as array.
     * @throws QueryException query exception.
     */
    public function queryMultiple(string $sQuery, string $sClassName): array
    {
        $this->initConnection();
        $oStatement = $this->getConnection()->prepare($sQuery);
        if (false === $oStatement->execute()) {
            throw new QueryException($sQuery, $oStatement->errorInfo()[2], intval($oStatement->errorCode()));
        }
        $aObjects = [];
        foreach ($oStatement->fetchAll(\PDO::FETCH_CLASS, $sClassName) as $oObject) {
            $aObjects [] = $oObject;
        }

        return $aObjects;
    }
}