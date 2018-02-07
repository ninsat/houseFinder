<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Repository;

/**
 * Class RepositoryFactoryTrait.
 *
 * @package YoannBlot\Framework\Service\Repository
 */
trait RepositoryFactoryTrait
{

    /**
     * @var FactoryService repository factory service.
     */
    protected $oRepositoryFactoryService = null;

    /**
     * @return FactoryService repository factory service.
     */
    protected function getRepositoryFactoryService(): FactoryService
    {
        return $this->oRepositoryFactoryService;
    }
}