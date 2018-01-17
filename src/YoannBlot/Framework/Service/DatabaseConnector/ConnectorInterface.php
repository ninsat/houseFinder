<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseConnector;

use YoannBlot\Framework\Model\DataBase\DataBaseConfig;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;

/**
 * Interface ConnectorInterface.
 *
 * @package YoannBlot\Framework\Service\DatabaseConnector
 */
interface ConnectorInterface
{

    /**
     * @return DataBaseConfig configuration.
     */
    public function getConfiguration(): DataBaseConfig;

    /**
     * Close the connection.
     */
    public function close();

    /**
     * Execute a query and return default array.
     *
     * @param string $sQuery query to execute
     * @return array data fetched.
     * @throws QueryException query exception.
     */
    public function fetchAll(string $sQuery): array;

    /**
     * Execute a simple query.
     *
     * @param string $sQuery query to execute
     *
     * @return bool true if success, otherwise false.
     * @throws QueryException query exception.
     */
    public function execute(string $sQuery): bool;

    /**
     * Query a single object.
     *
     * @param string $sQuery query to execute.
     * @param string $sClassName entity class name.
     *
     * @return AbstractEntity matched entity if found, otherwise null.
     * @throws EntityNotFoundException if entity was not found.
     * @throws QueryException query exception.
     */
    public function querySingle(string $sQuery, string $sClassName): AbstractEntity;

    /**
     * Query multiple objects.
     *
     * @param string $sQuery query to execute.
     * @param string $sClassName entity class name
     *
     * @return AbstractEntity[] matched entities as array.
     * @throws QueryException query exception.
     */
    public function queryMultiple(string $sQuery, string $sClassName): array;
}