<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseFinder;

use YoannBlot\HouseFinder\Model\Entity\User;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheService;
use YoannBlot\HouseFinder\Service\HouseCache\HouseCacheTrait;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerInterface;

/**
 * Class AbstractHouseFinder.
 *
 * @package YoannBlot\HouseFinder\Service\HouseFinder
 */
abstract class AbstractHouseFinder implements HouseCrawlerInterface
{
    use HouseCacheTrait;

    /**
     * @var User user.
     */
    private $oUser;

    /**
     * SeLogerService constructor.
     *
     * @param HouseCacheService $oCacheService cache service.
     */
    public function __construct(HouseCacheService $oCacheService)
    {
        $this->oHouseCacheService = $oCacheService;
        $this->oHouseCacheService->setHouseFinder($this);
    }

    /**
     * @inheritdoc
     */
    public function getUser(): User
    {
        return $this->oUser;
    }

    /**
     * @inheritdoc
     */
    public function getName(): string
    {
        $sName = get_called_class();
        $sName = substr($sName, strrpos($sName, '\\') + 1);
        $sName = substr($sName, 0, strrpos($sName, 'Service'));

        return $sName;
    }

    /**
     * @inheritdoc
     */
    public function process(User $oUser): bool
    {
        $this->oUser = $oUser;
        if (!$this->getHouseCache()->isValid()) {
            $this->getHouseCache()->save();
        }

        return $this->parse();
    }

    /**
     * Parse current house data.
     *
     * @return bool true if success, otherwise false.
     */
    protected abstract function parse(): bool;
}