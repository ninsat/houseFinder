<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

/**
 * Trait Title.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Title {

    /**
     * @var string title.
     */
    private $title = '';

    /**
     * @return string
     */
    public function getTitle (): string {
        return $this->title;
    }

    /**
     * @param string $sTitle
     */
    public function setTitle (string $sTitle) {
        if (strlen($sTitle) > 2) {
            $this->title = $sTitle;
        }
    }
}