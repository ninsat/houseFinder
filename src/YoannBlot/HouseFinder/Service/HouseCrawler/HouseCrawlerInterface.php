<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Service\HouseCrawler;

use YoannBlot\HouseFinder\Model\Entity\User;

/**
 * Interface HouseCrawlerInterface.
 *
 * @package YoannBlot\HouseFinder\Service\HouseCrawler
 */
interface HouseCrawlerInterface
{
    /**
     * @return string house crawler name.
     */
    public function getName(): string;

    /**
     * @return string house crawler URL.
     */
    public function getUrl(): string;

    /**
     * Process current house crawler.
     *
     * @param User $oUser user preferences to process.
     * @return bool true if success, otherwise false.
     */
    public function process(User $oUser): bool;

    /**
     * Get the current user preferences.
     *
     * @return User user.
     */
    public function getUser(): User;
}