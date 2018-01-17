<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity\Common;

use YoannBlot\Framework\Model\DataBase\Annotation\Nullable;
use YoannBlot\Framework\Validator\Boolean;

/**
 * Trait Enabled.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Enabled {

    /**
     * @var bool is city enabled.
     * @Nullable(false)
     */
    private $enabled = true;

    /**
     * @return boolean
     */
    public function isEnabled (): bool {
        return $this->enabled;
    }

    /**
     * @param boolean $bEnabled
     */
    public function setEnabled (bool $bEnabled) {
        $this->enabled = Boolean::getValue($bEnabled);
    }
}