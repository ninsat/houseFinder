<?php

namespace YoannBlot\HouseFinder\Command\Finder;

use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Service\Logger\LoggerService;
use YoannBlot\HouseFinder\Model\Entity\City;
use YoannBlot\HouseFinder\Model\Entity\User;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerService;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerTrait;

/**
 * Class RetrieveHousesCommand.
 *
 * @package YoannBlot\HouseFinder\Command\Finder
 */
class RetrieveHousesCommand extends AbstractCommand
{

    use HouseCrawlerTrait;

    /**
     * RetrieveHousesCommand constructor.
     *
     * @param LoggerService $oLogger logger
     * @param HouseCrawlerService $oHouseCrawler house crawler
     */
    public function __construct(LoggerService $oLogger, HouseCrawlerService $oHouseCrawler)
    {
        parent::__construct($oLogger);
        $this->oHouseCrawler = $oHouseCrawler;
    }

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        // TODO retrieve parameter user
        $oUser = new User();
        $oUser->setId(1);
        $oUser->setRent(1200);
        $oUser->setSurface(70);

        $oPoissy = new City();
        $oPoissy->setName('Poissy');
        $oPoissy->setPostalCode('78300');
        $oUser->addCity($oPoissy);

        return $this->oHouseCrawler->run($oUser);
    }

}