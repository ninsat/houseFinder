<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Exception;

/**
 * Class RepositoryNotFoundException.
 *
 * @package YoannBlot\Framework\Model\Exception
 * @author  Yoann Blot
 */
class RepositoryNotFoundException extends DataBaseException
{
    /**
     * RepositoryNotFoundException constructor.
     *
     * @param string $sRepositoryName repository name.
     */
    public function __construct(string $sRepositoryName)
    {
        parent::__construct("Repository with name '$sRepositoryName' not found.");
    }
}