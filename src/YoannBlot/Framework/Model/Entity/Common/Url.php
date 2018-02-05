<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Url.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Url
{

    /**
     * @var string url.
     * @Length(255)
     */
    private $url = '';

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $sUrl
     */
    public function setUrl(string $sUrl)
    {
        if (strlen($sUrl) > 2) {
            $this->url = $sUrl;
        }
    }
}