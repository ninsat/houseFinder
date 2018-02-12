<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Service\Repository;

use YoannBlot\Framework\DependencyInjection\Container;
use YoannBlot\Framework\Model\Exception\RepositoryNotFoundException;
use YoannBlot\Framework\Model\Repository\AbstractRepository;

/**
 * Class FactoryService.
 *
 * @package YoannBlot\Framework\Service\Repository
 */
class FactoryService
{
    /**
     * @var AbstractRepository[] repositories, key is entity class, value is repository instance.
     */
    private $aRepositories = [];

    /**
     * Get repository from entity.
     *
     * @param string $sEntityClass entity class.
     *
     * @return AbstractRepository|null matched repository.
     */
    public function getRepository(string $sEntityClass): ?AbstractRepository
    {
        try {
            if (!array_key_exists($sEntityClass, $this->aRepositories)) {
                $this->loadRepository($sEntityClass);
            }
            $oRepository = $this->aRepositories[$sEntityClass];
        } catch (RepositoryNotFoundException $e) {
            $oRepository = null;
        }

        return $oRepository;
    }

    /**
     * Load a repository.
     *
     * @param string $sEntityClass entity class.
     *
     * @throws RepositoryNotFoundException repository not found.
     */
    private function loadRepository(string $sEntityClass): void
    {
        $sRepositoryName = str_replace('Entity', 'Repository', $sEntityClass) . 'Repository';
        if (0 === strpos($sRepositoryName, '\\')) {
            $sRepositoryName = substr($sRepositoryName, 1);
        }
        $oRepository = Container::getInstance()->getService($sRepositoryName);
        if (null === $oRepository) {
            throw new RepositoryNotFoundException($sRepositoryName);
        }
        $this->aRepositories[$sEntityClass] = $oRepository;
    }

    /**
     * Get all repositories.
     *
     * @return AbstractRepository[] all repositories.
     */
    public function getAll(): array
    {
        return $this->aRepositories;
    }
}