<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseConnector;

use YoannBlot\Framework\Model\DataBase\ConfigurationConstants;
use YoannBlot\Framework\Model\DataBase\DataBaseConfig;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\Framework\Service\ConfigurationLoader\LoaderInterface;

/**
 * Class PdoService.
 *
 * @package YoannBlot\Framework\Service\DatabaseConnector
 */
class PdoService implements ConnectorInterface
{

    /**
     * @var \PDO database connection object.
     */
    private $oConnection = null;

    /**
     * @var LoaderInterface configuration loader service.
     */
    private $oLoaderService = null;

    /**
     * @var DataBaseConfig database configuration.
     */
    private $oConfiguration = null;

    /**
     * PdoService constructor.
     *
     * @param LoaderInterface $oLoaderService
     */
    public function __construct(LoaderInterface $oLoaderService)
    {
        $this->oLoaderService = $oLoaderService;
        $this->initConnection();
    }

    /**
     * @return LoaderInterface loader service.
     */
    private function getLoader(): LoaderInterface
    {
        return $this->oLoaderService;
    }

    /**
     * @inheritdoc
     */
    public function getConfiguration(): DataBaseConfig
    {
        return $this->oConfiguration;
    }

    /**
     * Open a connection to database.
     */
    private function initConnection()
    {
        if (!$this->isConnected()) {
            $this->getLoader()->load(ConfigurationConstants::PATH);
            $this->oConfiguration = new DataBaseConfig();
            $this->getConfiguration()->setHost($this->getLoader()->get(ConfigurationConstants::HOST,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setPort(intval($this->getLoader()->get(ConfigurationConstants::PORT,
                ConfigurationConstants::SECTION)));
            $this->getConfiguration()->setUsername($this->getLoader()->get(ConfigurationConstants::USER,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setPassword($this->getLoader()->get(ConfigurationConstants::PASSWORD,
                ConfigurationConstants::SECTION));
            $this->getConfiguration()->setDatabaseName($this->getLoader()->get(ConfigurationConstants::DATABASE_NAME,
                ConfigurationConstants::SECTION));

            $sConnectionSettings = 'mysql:host=' . $this->getConfiguration()->getHost() . ':' . $this->getConfiguration()->getPort() . ';dbname=' . $this->getConfiguration()->getDatabaseName();
            $this->oConnection = new \PDO($sConnectionSettings, $this->getConfiguration()->getUsername(),
                $this->getConfiguration()->getPassword());
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
     * @inheritdoc
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
     * @inheritdoc
     */
    public function execute(string $sQuery): bool
    {
        $this->initConnection();
        return false !== $this->getConnection()->exec($sQuery);
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
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