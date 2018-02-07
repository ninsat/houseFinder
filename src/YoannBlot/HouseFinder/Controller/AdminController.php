<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\Helper\CityTrait;
use YoannBlot\HouseFinder\Model\Repository\UserRepository;

/**
 * Class AdminController.
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/admin")
 */
class AdminController extends AbstractUserController
{

    use CityTrait;

    /**
     * AdminController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param UserRepository $oUserRepository user repository.
     * @param CityRepository $oCityRepository city repository.
     */
    public function __construct(
        LoggerInterface $oLogger,
        $debug,
        UserRepository $oUserRepository,
        CityRepository $oCityRepository
    ) {
        parent::__construct($oLogger, $debug, $oUserRepository);
        $this->oCityRepository = $oCityRepository;
    }

    /**
     * @return array
     *
     * @path("/city")
     */
    public function cityRoute(): array
    {
        return [
            'cities' => $this->getCityRepository()->getAll()
        ];
    }

}