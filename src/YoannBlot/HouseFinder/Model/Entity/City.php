<?php

namespace YoannBlot\HouseFinder\Model\Entity;

use YoannBlot\Framework\Model\Entity\AbstractEntity;
use YoannBlot\Framework\Model\Entity\Common\{
    Enabled, Name
};
use YoannBlot\HouseFinder\Model\Entity\Common\PostalCode;

/**
 * Class City
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 */
final class City extends AbstractEntity {

    use Enabled, Name, PostalCode;

}