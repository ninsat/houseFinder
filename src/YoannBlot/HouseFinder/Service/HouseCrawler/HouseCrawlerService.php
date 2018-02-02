<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCrawler;

use YoannBlot\HouseFinder\Model\Entity\User;

/**
 * Class HouseCrawlerService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCrawler
 */
class HouseCrawlerService
{

    /**
     * @var HouseCrawlerInterface[] active house finders.
     */
    private $aHouseFinders = [];

    /**
     * HouseCrawlerService constructor.
     *
     * @param HouseCrawlerInterface[] $houseFinderServicesList property finders.
     */
    public function __construct(array $houseFinderServicesList)
    {
        $this->aHouseFinders = $houseFinderServicesList;
    }

    /**
     * Start crawling current house.
     *
     * @param User $oUser user.
     *
     * @return bool true if success, otherwise false.
     */
    public function run(User $oUser): bool
    {
        $bSuccess = true;
        foreach ($this->aHouseFinders as $oHouseFinder) {
            if (!$oHouseFinder->process($oUser)) {
                $bSuccess = false;
            }
        }

        return $bSuccess;
    }
}