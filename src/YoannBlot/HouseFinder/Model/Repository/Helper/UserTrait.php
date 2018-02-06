<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Repository\Helper;

use YoannBlot\HouseFinder\Model\Repository\UserRepository;

/**
 * Class UserTrait.
 *
 * @package YoannBlot\HouseFinder\Model\Repository\Helper
 */
trait UserTrait
{

    /**
     * @var UserRepository house repository.
     */
    private $oUserRepository = null;

    /**
     * @return UserRepository house repository.
     */
    protected function getUserRepository(): UserRepository
    {
        return $this->oUserRepository;
    }

}