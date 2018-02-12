<?php

namespace YoannBlot\HouseFinder\Command\Finder;

use YoannBlot\Framework\Command\AbstractCommand;
use YoannBlot\Framework\Model\Exception\ParameterException;
use YoannBlot\Framework\Service\Logger\LoggerService;
use YoannBlot\HouseFinder\Model\Repository\Helper\UserTrait;
use YoannBlot\HouseFinder\Model\Repository\UserRepository;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerService;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerTrait;

/**
 * Class RetrieveHousesCommand.
 *
 * @package YoannBlot\HouseFinder\Command\Finder
 */
class RetrieveHousesCommand extends AbstractCommand
{

    use HouseCrawlerTrait, UserTrait;

    /**
     * RetrieveHousesCommand constructor.
     *
     * @param LoggerService $oLogger logger
     * @param HouseCrawlerService $oHouseCrawler house crawler
     * @param UserRepository $oUserRepository user repository.
     */
    public function __construct(
        LoggerService $oLogger,
        HouseCrawlerService $oHouseCrawler,
        UserRepository $oUserRepository
    ) {
        parent::__construct($oLogger);
        $this->oHouseCrawler = $oHouseCrawler;
        $this->oUserRepository = $oUserRepository;
    }

    /**
     * @inheritdoc
     */
    public function run(): bool
    {
        $bSuccess = false;
        try {
            $oUser = $this->oUserRepository->get(intval($this->getParameter()));
            if (null !== $oUser) {
                $bSuccess = $this->oHouseCrawler->run($oUser);
            }
        } catch (ParameterException $oException) {
            $bSuccess = false;
        }

        return $bSuccess;
    }

}