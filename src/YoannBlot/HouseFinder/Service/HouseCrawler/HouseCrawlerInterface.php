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
     * Generate URL for retrieving all matched links.
     *
     * @return string base URL.
     */
    public function generateUrl(): string;

    /**
     * Process current house crawler to retrieve valid links.
     *
     * @param User $oUser user preferences to process.
     *
     * @return bool true if success, otherwise false.
     */
    public function processLinks(User $oUser): bool;

    /**
     * Get the current user preferences.
     *
     * @return User user.
     */
    public function getUser(): User;

    /**
     * Parse all houses.
     *
     * @param User $oUser user preferences to process.
     *
     * @return bool true if success, otherwise false.
     */
    public function parseHouses(User $oUser): bool;
}