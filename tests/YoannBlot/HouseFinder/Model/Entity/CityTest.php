<?php
declare(strict_types=1);

namespace YoannBlot\HouseFinder\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Class CityTest
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 *
 * @cover   City
 */
class CityTest extends TestCase
{

    /**
     * Test city name.
     */
    public function testName()
    {
        $oCity = new City();

        // default value
        static::assertEmpty($oCity->getName());

        // invalid name
        $oCity->setName('');
        static::assertEmpty($oCity->getName());
        $oCity->setName('te');
        static::assertEmpty($oCity->getName());

        // valid name
        $sName = 'test';
        $oCity->setName($sName);
        static::assertEquals($sName, $oCity->getName());
    }

    /**
     * Test city postal code.
     */
    public function testPostalCode()
    {
        $oCity = new City();

        // default value
        static::assertEmpty($oCity->getPostalCode());

        // invalid postal code
        $oCity->setPostalCode('');
        static::assertEmpty($oCity->getPostalCode());
        $oCity->setPostalCode('te');
        static::assertEmpty($oCity->getPostalCode());

        // valid postal code
        $sPostalCode = '2B033';
        $oCity->setPostalCode($sPostalCode);
        static::assertEquals($sPostalCode, $oCity->getPostalCode());
    }
}
