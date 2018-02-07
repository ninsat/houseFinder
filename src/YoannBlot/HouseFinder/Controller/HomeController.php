<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;
use YoannBlot\HouseFinder\Model\Repository\Helper\CityTrait;
use YoannBlot\HouseFinder\Model\Repository\UserRepository;

/**
 * Class HomeController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/")
 */
class HomeController extends AbstractUserController
{

    use CityTrait;

    /**
     * HomeController constructor.
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
     * @path("")
     */
    public function indexRoute(): array
    {
        return [
            'cities' => $this->getCityRepository()->getAllAvailable($this->getUser())
        ];
    }
}