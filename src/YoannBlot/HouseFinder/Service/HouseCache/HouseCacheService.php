<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCache;

use YoannBlot\Framework\Utils\File\Directory;

/**
 * Class HouseCacheService.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCache
 */
class HouseCacheService
{

    /**
     * Cache expiration in seconds.
     */
    const EXPIRE = 3600;

    /**
     * @var string file name.
     */
    private $sFileName = '';

    /**
     * Check if given cache file name is valid.
     *
     * @param string $sNewCacheFileName cache file name.
     * @param int $iExpirationTime expiration time in seconds.
     *
     * @return bool true if cache is valid.
     */
    public function isValid(string $sNewCacheFileName = '', int $iExpirationTime = self::EXPIRE): bool
    {
        if ('' !== $sNewCacheFileName) {
            $this->sFileName = $sNewCacheFileName;
        }
        $bValid = false;
        if (is_file($this->getPath())) {
            $iUpdateTimestamp = filemtime($this->getPath());
            if (\time() - $iUpdateTimestamp < $iExpirationTime) {
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
        return $this->getDirectory() . $this->sFileName;
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

        Directory::create($sDirectory);

        return $sDirectory;
    }

    /**
     * Save current content into cache file.
     *
     * @param string $sContent content to save.
     * @param string $sNewCacheFileName cache file name.
     *
     * @return bool true if success, otherwise false.
     */
    public function save(string $sContent, string $sNewCacheFileName = ''): bool
    {
        if ('' !== $sNewCacheFileName) {
            $this->sFileName = $sNewCacheFileName;
        }
        Directory::create(dirname($this->getPath()));

        return file_put_contents($this->getPath(), trim($sContent)) > 0;
    }

    /**
     * @return string cache content.
     */
    public function getContent(): string
    {
        return file_get_contents($this->getPath());
    }
}