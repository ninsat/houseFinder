<?php

namespace YoannBlot\HouseFinder\Model\Entity;

/**
 * Class CityTest
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 *
 * @cover City
 */
class CityTest extends \PHPUnit_Framework_TestCase {

    /**
     * Test city name.
     */
    public function testName () {
        $oCity = new City();

        // default value
        $this->assertEmpty($oCity->getName());

        // invalid name
        $oCity->setName('');
        $this->assertEmpty($oCity->getName());
        $oCity->setName('te');
        $this->assertEmpty($oCity->getName());

        // valid name
        $sName = 'test';
        $oCity->setName($sName);
        $this->assertEquals($sName, $oCity->getName());
    }

    /**
     * Test city postal code.
     */
    public function testPostalCode () {
        $oCity = new City();

        // default value
        $this->assertEmpty($oCity->getPostalCode());

        // invalid postal code
        $oCity->setPostalCode('');
        $this->assertEmpty($oCity->getPostalCode());
        $oCity->setPostalCode('te');
        $this->assertEmpty($oCity->getPostalCode());

        // valid postal code
        $sPostalCode = 'test';
        $oCity->setPostalCode($sPostalCode);
        $this->assertEquals($sPostalCode, $oCity->getPostalCode());
    }

    /**
     * Test city enabled.
     */
    public function testEnabled () {
        $oCity = new City();

        // default value
        $this->assertTrue($oCity->isEnabled());

        // invalid
        $oCity->setEnabled('');
        $this->assertFalse($oCity->isEnabled());
        $oCity->setEnabled(156);
        $this->assertTrue($oCity->isEnabled());

        // valid
        $oCity->setEnabled(false);
        $this->assertFalse($oCity->isEnabled());
        $oCity->setEnabled(true);
        $this->assertTrue($oCity->isEnabled());
    }
}
