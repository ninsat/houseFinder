<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCrawler;

/**
 * Class HouseCrawlerTrait.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCrawler
 */
trait HouseCrawlerTrait
{
    /**
     * @var HouseCrawlerService house crawler service.
     */
    private $oHouseCrawler;

    /**
     * @return HouseCrawlerService house crawler service.
     */
    protected function getHouseCrawler(): HouseCrawlerService
    {
        return $this->oHouseCrawler;
    }

}