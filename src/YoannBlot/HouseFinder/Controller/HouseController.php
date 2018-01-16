<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Controller;

use Psr\Log\LoggerInterface;
use YoannBlot\Framework\Controller\AbstractController;
use YoannBlot\Framework\Controller\Exception\Redirect404Exception;
use YoannBlot\Framework\Model\Exception\DataBaseException;
use YoannBlot\HouseFinder\Model\Repository\Helper\HouseTrait;
use YoannBlot\HouseFinder\Model\Repository\HouseRepository;

/**
 * Class HouseController
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 *
 * @path("/house")
 */
class HouseController extends AbstractController
{

    use HouseTrait;

    /**
     * HomeController constructor.
     *
     * @param LoggerInterface $oLogger logger.
     * @param bool $debug debug mode.
     * @param HouseRepository $oHouseRepository house repository.
     */
    public function __construct(LoggerInterface $oLogger, $debug, HouseRepository $oHouseRepository)
    {
        parent::__construct($oLogger, $debug);
        $this->oHouseRepository = $oHouseRepository;
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
        } catch (DataBaseException $e) {
            throw new Redirect404Exception("House not found for id '$iHouseId'.");
        }

        return [
            'house' => $oHouse
        ];
    }
}