<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\DataBase\Annotation\ManyToMany;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\HouseFinder\Model\Entity\Common\{
    Bath, Bedrooms, LinkToCities, MaxPrice, Pieces, Rent, Rental, Surface
};

/**
 * Class User
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class User extends AbstractEntity
{

    use Rental, Pieces, Bedrooms, Surface, Bath, Rent, MaxPrice, LinkToCities;

    /**
     * @var \YoannBlot\HouseFinder\Model\Entity\City[] linked cities.
     * @ManyToMany()
     */
    protected $cities = [];

    /**
     * @inheritdoc
     */
    public function __toString(): string
    {
        $sString = '';
        if (AbstractEntity::DEFAULT_ID === $this->getId()) {
            $sString .= '[NEW User]';
        } else {
            $sString .= '[User #' . $this->getId() . ']';
        }
        $sString .= " searches cities : ";
        foreach ($this->getCities() as $oCity) {
            $sString .= $oCity;
        }

        return $sString;
    }
}