<?php

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\HouseFinder\Model\Entity\User;
use YoannBlot\HouseFinder\Model\Repository\Helper\UserTrait;
use YoannBlot\HouseFinder\Model\Repository\UserRepository;

/**
 * Class AbstractUserController.
 * Common controller handling user authentication.
 *
 * @package YoannBlot\HouseFinder\Controller
 */
abstract class AbstractUserController extends AbstractController
{
    use UserTrait;

    /**
     * @var User|null current user.
     */
    private $oUser = null;

    /**
     * AbstractUserController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param UserRepository $oUserRepository user repository.
     */
    public function __construct(LoggerInterface $oLogger, $debug, UserRepository $oUserRepository)
    {
        parent::__construct($oLogger, $debug);
        $this->oUserRepository = $oUserRepository;
    }

    /**
     * @return User user.
     */
    protected function getUser(): User
    {
        if (null === $this->oUser) {
            $iId = 1;
            // TODO retrieve from session / cookie
            $this->oUser = $this->getUserRepository()->get($iId);
        }

        return $this->oUser;
    }
}