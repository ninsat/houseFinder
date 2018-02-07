<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\HouseFinder\Model\Repository\Helper\HouseTrait;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;
use YoannBlot\HouseFinder\Model\Repository\UserRepository;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesService;
use YoannBlot\HouseFinder\Service\HouseImages\HouseImagesTrait;

/**
 * Class HouseController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/house")
 */
class HouseController extends AbstractUserController
{

    use HouseTrait, HouseImagesTrait;

    /**
     * HomeController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param UserRepository $oUserRepository user repository.
     * @param HouseRepository $oHouseRepository house repository.
     * @param HouseImagesService $oHouseImagesService house images service.
     */
    public function __construct(
        LoggerInterface $oLogger,
        $debug,
        UserRepository $oUserRepository,
        HouseRepository $oHouseRepository,
        HouseImagesService $oHouseImagesService
    ) {
        parent::__construct($oLogger, $debug, $oUserRepository);
        $this->oHouseRepository = $oHouseRepository;
        $this->oHouseImagesService = $oHouseImagesService;
    }

    /**
     * House page.
     *
     * @param int $iHouseId house id.
     * @return array
     *
     * @path("/([0-9]+)")
     * @throws Redirect404Exception house not found.
     */
    public function indexRoute(int $iHouseId): array
    {
        try {
            $oHouse = $this->getHouseRepository()->get($iHouseId);
            $this->getHouseImages()->loadAll($oHouse);
        } catch (DataBaseException $e) {
            throw new Redirect404Exception("House not found for id '$iHouseId'.");
        }

        return [
            'house' => $oHouse
        ];
    }
}