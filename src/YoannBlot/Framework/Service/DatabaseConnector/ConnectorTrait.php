<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\DatabaseConnector;

/**
 * Trait ConnectorTrait.
 *
 * @package YoannBlot\Framework\Service\DatabaseConnector
 */
trait ConnectorTrait
{
    /**
     * @var ConnectorInterface connector service.
     */
    private $oConnector = null;

    /**
     * @return ConnectorInterface connector.
     */
    protected function getConnector(): ConnectorInterface
    {
        return $this->oConnector;
    }
}