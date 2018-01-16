<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

/**
 * Trait Url.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Url {

    /**
     * @var string url.
     */
    private $url = '';

    /**
     * @return string
     */
    public function getUrl (): string {
        return $this->url;
    }

    /**
     * @param string $sUrl
     */
    public function setUrl (string $sUrl) {
        if (strlen($sUrl) > 2) {
            $this->url = $sUrl;
        }
    }
}