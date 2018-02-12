<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Entity\Common\Name;
use YoannBlot\HouseFinder\Model\Entity\Common\PostalCode;

/**
 * Class City
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class City extends AbstractEntity
{

    use Name, PostalCode;

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        $sString = '';
        if (AbstractEntity::DEFAULT_ID === $this->getId()) {
            $sString .= '[NEW City]';
        } else {
            $sString .= '[City #' . $this->getId() . ']';
        }
        $sString .= ' ' . $this->getName() . ' (' . $this->getPostalCode() . ')';

        return $sString;
    }
}