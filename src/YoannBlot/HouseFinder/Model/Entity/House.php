<?php

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Entity\Common\{
    Date, Description, Enabled, Title, Url
};
use YoannBlot\HouseFinder\Model\Entity\Common\{
    Bath, Bedrooms, Fees, Guarantee, Pieces, Rent, SiteId, Surface, Type
};

/**
 * Class House
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class House extends AbstractEntity {

    use Title, Type, Description,
        Pieces, Bedrooms, Surface, Bath,
        Rent, Fees, Guarantee,
        Enabled, Date, SiteId, Url;

    /**
     * House constructor.
     */
    public function __construct () {
        $this->setDate(new \DateTime("now"));
    }

}