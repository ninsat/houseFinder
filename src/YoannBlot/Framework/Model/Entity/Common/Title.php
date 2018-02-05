<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Length;

/**
 * Trait Title.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Title
{

    /**
     * @var string title.
     * @Length(value=200)
     */
    private $title = '';

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $sTitle
     */
    public function setTitle(string $sTitle)
    {
        if (strlen($sTitle) > 2) {
            $this->title = $sTitle;
        }
    }
}