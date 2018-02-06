<?php

namespace YoannBlot\Framework\Model\Repository;

/**
 * Class Loader.
 *
 * @package YoannBlot\Framework\Model\Repository
 */
final class Loader
{
    /**
     * Get a repository by its name.
     *
     * @param string $sRepositoryName repository name.
     *
     * @return null|AbstractRepository repository.
     */
    public static function get(string $sRepositoryName): ?AbstractRepository
    {
        $sRepositoryName = "YoannBlot\HouseFinder\Model\Repository\\" . ucfirst($sRepositoryName) . 'Repository';

        /** @var AbstractRepository $oRepository */
        return new $sRepositoryName();
    }
}