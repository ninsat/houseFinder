<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\DataBase\Annotation\ManyToMany;
use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\HouseFinder\Model\Entity\Common\{
    Bath, Bedrooms, LinkToCities, Pieces, Rent, Surface, Type
};

/**
 * Class User
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class User extends AbstractEntity
{

    use Type, Pieces, Bedrooms, Surface, Bath, Rent, LinkToCities;

    /**
     * @var \YoannBlot\HouseFinder\Model\Entity\City[] linked cities.
     * @ManyToMany(table="houses_user_city", current_id="user_id", foreign_id="city_id")
     */
    protected $cities = [];

}