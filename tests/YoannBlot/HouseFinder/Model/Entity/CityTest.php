<?php

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
class CityTest extends TestCase {

    /**
     * Test city name.
     */
    public function testName () {
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
    public function testPostalCode () {
        $oCity = new City();

        // default value
        static::assertEmpty($oCity->getPostalCode());

        // invalid postal code
        $oCity->setPostalCode('');
        static::assertEmpty($oCity->getPostalCode());
        $oCity->setPostalCode('te');
        static::assertEmpty($oCity->getPostalCode());

        // valid postal code
        $sPostalCode = 'test';
        $oCity->setPostalCode($sPostalCode);
        static::assertEquals($sPostalCode, $oCity->getPostalCode());
    }

    /**
     * Test city enabled.
     */
    public function testEnabled () {
        $oCity = new City();

        // default value
        static::assertTrue($oCity->isEnabled());

        // invalid
        $oCity->setEnabled('');
        static::assertFalse($oCity->isEnabled());
        $oCity->setEnabled(156);
        static::assertTrue($oCity->isEnabled());

        // valid
        $oCity->setEnabled(false);
        static::assertFalse($oCity->isEnabled());
        $oCity->setEnabled(true);
        static::assertTrue($oCity->isEnabled());
    }
}
