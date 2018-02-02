<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCache;

use YoannBlot\Framework\Utils\File\Directory;
use YoannBlot\HouseFinder\Service\HouseCrawler\HouseCrawlerInterface;

/**
 * Class HouseCacheService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCache
 */
class HouseCacheService
{

    /**
     * Cache file suffix.
     */
    const SUFFIX = '.cache';

    /**
     * Cache expiration in seconds.
     */
    const EXPIRE = 3600;

    /**
     * @var HouseCrawlerInterface
     */
    private $oHouseFinder = null;

    /**
     * Set the house finder.
     *
     * @param HouseCrawlerInterface $oHouseFinder house finder.
     */
    public function setHouseFinder(HouseCrawlerInterface $oHouseFinder): void
    {
        $this->oHouseFinder = $oHouseFinder;
    }

    /**
     * @return bool true if cache is valid.
     */
    public function isValid(): bool
    {
        $bValid = false;
        if (is_file($this->getPath())) {
            $iUpdateTimestamp = filemtime($this->getPath());
            if (\time() - $iUpdateTimestamp < static::EXPIRE) {
                $bValid = true;
            }
        }

        return $bValid;
    }

    /**
     * @return string cache path.
     */
    public function getPath(): string
    {
        return $this->getDirectory() . $this->getHouseFinder()->getName() . static::SUFFIX;
    }

    /**
     * @return string cache directory.
     */
    protected function getDirectory(): string
    {
        $sDirectory = '';
        $sDirectory .= ROOT_PATH . 'var' . DIRECTORY_SEPARATOR;
        $sDirectory .= 'cache' . DIRECTORY_SEPARATOR;
        $sDirectory .= 'houseFinder' . DIRECTORY_SEPARATOR;
        $sDirectory .= $this->getHouseFinder()->getUser()->getId() . DIRECTORY_SEPARATOR;
        $sDirectory .= $this->getHouseFinder()->getName() . DIRECTORY_SEPARATOR;

        Directory::create($sDirectory);

        return $sDirectory;
    }

    /**
     * @return HouseCrawlerInterface current house crawler.
     */
    private function getHouseFinder(): HouseCrawlerInterface
    {
        return $this->oHouseFinder;
    }

    /**
     * Save current content into cache file.
     *
     * @return bool true if success, otherwise false.
     */
    public function save(): bool
    {
        return file_put_contents($this->getPath(), file_get_contents($this->getHouseFinder()->getUrl())) > 0;
    }

    /**
     * @return string cache content.
     */
    public function getContent(): string
    {
        return file_get_contents($this->getPath());
    }
}