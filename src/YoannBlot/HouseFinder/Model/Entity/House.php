<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Entity\Common\{
    Date, Description, Enabled, Title, Url
};
use YoannBlot\HouseFinder\Model\Entity\Common\{
    Bath, Bedrooms, Fees, Guarantee, LinkToCity, Pieces, Rent, Surface, Type
};

/**
 * Class House
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class House extends AbstractEntity
{

    use Title, Type, Description,
        Pieces, Bedrooms, Surface, Bath,
        Rent, Fees, Guarantee,
        Enabled, Date, Url,
        LinkToCity;

    /**
     * House constructor.
     */
    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
    }

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        $sString = '';
        if (AbstractEntity::DEFAULT_ID === $this->getId()) {
            $sString .= '[NEW House]';
        } else {
            $sString .= '[House #' . $this->getId() . ']';
        }
        $sString .= ' ' . $this->getTitle() . ' (' . $this->getCity() . ')';

        return $sString;
    }
}