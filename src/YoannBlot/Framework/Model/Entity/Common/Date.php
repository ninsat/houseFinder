<?php

namespace YoannBlot\Framework\Model\Entity\Common;

/**
 * Trait Date.
 *
 * @package YoannBlot\Framework\Model\Entity\Common
 */
trait Date {

    /**
     * @var \DateTime date.
     */
    private $date;

    /**
     * @return \DateTime
     */
    public function getDate (): \DateTime {
        return $this->date;
    }

    /**
     * @param \DateTime $oDate
     */
    public function setDate (\DateTime $oDate) {
        $this->date = $oDate;
    }
}