<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity\Common;

/**
 * Trait SiteId.
 *
 * @package YoannBlot\HouseFinder\Model\Entity\Common
 */
trait SiteId {

    /**
     * @var string site id.
     */
    private $site_id = '';

    /**
     * @return string
     */
    public function getSiteId (): string {
        return $this->site_id;
    }

    /**
     * @param string $sSiteId
     */
    public function setSiteId (string $sSiteId) {
        if (strlen($sSiteId) > 0) {
            $this->site_id = $sSiteId;
        }
    }

}