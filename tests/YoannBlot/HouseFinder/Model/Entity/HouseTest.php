<?php

namespace YoannBlot\HouseFinder\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Class HouseTest.
 *
 * @package YoannBlot\HouseFinder\Model\Entity
 * @author  Yoann Blot
 *
 * @cover   House
 */
class HouseTest extends TestCase {

    /**
     * Test house title.
     */
    public function testTitle () {
        $oHouse = new House();

        // default value
        static::assertEmpty($oHouse->getTitle());

        // invalid title
        $oHouse->setTitle('');
        static::assertEmpty($oHouse->getTitle());
        $oHouse->setTitle('te');
        static::assertEmpty($oHouse->getTitle());

        // valid title
        $sTitle = 'house title';
        $oHouse->setTitle($sTitle);
        static::assertEquals($sTitle, $oHouse->getTitle());
    }

    /**
     * Test house enabled.
     */
    public function testEnabled () {
        $oHouse = new House();

        // default value
        static::assertTrue($oHouse->isEnabled());

        // invalid
        $oHouse->setEnabled('');
        static::assertFalse($oHouse->isEnabled());
        $oHouse->setEnabled(156);
        static::assertTrue($oHouse->isEnabled());

        // valid
        $oHouse->setEnabled(false);
        static::assertFalse($oHouse->isEnabled());
        $oHouse->setEnabled(true);
        static::assertTrue($oHouse->isEnabled());
    }

    /**
     * Test house description.
     */
    public function testDescription () {
        $oHouse = new House();

        // default value
        static::assertEmpty($oHouse->getDescription());

        // invalid description
        $oHouse->setDescription('');
        static::assertEmpty($oHouse->getDescription());
        $oHouse->setDescription('te');
        static::assertEmpty($oHouse->getDescription());

        // valid description
        $sDescription = 'house description which can be huge if we put a lot of things inside it...';
        $oHouse->setDescription($sDescription);
        static::assertEquals($sDescription, $oHouse->getDescription());
    }

    /**
     * Test house url.
     */
    public function testUrl () {
        $oHouse = new House();

        // default value
        static::assertEmpty($oHouse->getUrl());

        // invalid url
        $oHouse->setUrl('');
        static::assertEmpty($oHouse->getUrl());
        $oHouse->setUrl('te');
        static::assertEmpty($oHouse->getUrl());

        // valid url
        $sUrl = 'http://www.yoannblot.com';
        $oHouse->setUrl($sUrl);
        static::assertEquals($sUrl, $oHouse->getUrl());
    }

    /**
     * Test house type.
     */
    public function testType () {
        $oHouse = new House();

        // default value
        static::assertEmpty($oHouse->getType());

        // invalid type
        $oHouse->setType('');
        static::assertEmpty($oHouse->getType());
        $oHouse->setType('te');
        static::assertEmpty($oHouse->getType());

        // valid type
        $sType = 'leboncoin';
        $oHouse->setType($sType);
        static::assertEquals($sType, $oHouse->getType());
    }

    /**
     * Test house site id.
     */
    public function testSiteId () {
        $oHouse = new House();

        // default value
        static::assertEmpty($oHouse->getSiteId());

        // invalid site id
        $oHouse->setSiteId('');
        static::assertEmpty($oHouse->getSiteId());

        // valid site id
        $sSiteId = 'XX-YY';
        $oHouse->setSiteId($sSiteId);
        static::assertEquals($sSiteId, $oHouse->getSiteId());
    }

    /**
     * Test house pieces.
     */
    public function testPieces () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getPieces());

        // invalid
        $oHouse->setPieces(-1);
        static::assertEquals(0, $oHouse->getPieces());
        $oHouse->setPieces(1560);
        static::assertEquals(0, $oHouse->getPieces());

        // valid
        $iPieces = 2;
        $oHouse->setPieces($iPieces);
        static::assertEquals($iPieces, $oHouse->getPieces());
        $iPieces = 3;
        $oHouse->setPieces($iPieces);
        static::assertEquals($iPieces, $oHouse->getPieces());
        $iPieces = 5;
        $oHouse->setPieces($iPieces);
        static::assertEquals($iPieces, $oHouse->getPieces());
    }

    /**
     * Test house bedrooms .
     */
    public function testBedrooms () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getBedrooms());

        // invalid
        $oHouse->setBedrooms(-1);
        static::assertEquals(0, $oHouse->getBedrooms());
        $oHouse->setBedrooms(1560);
        static::assertEquals(0, $oHouse->getBedrooms());

        // valid
        $iBedrooms = 2;
        $oHouse->setBedrooms($iBedrooms);
        static::assertEquals($iBedrooms, $oHouse->getBedrooms());
        $iBedrooms = 3;
        $oHouse->setBedrooms($iBedrooms);
        static::assertEquals($iBedrooms, $oHouse->getBedrooms());
        $iBedrooms = 5;
        $oHouse->setBedrooms($iBedrooms);
        static::assertEquals($iBedrooms, $oHouse->getBedrooms());
    }

    /**
     * Test house surface .
     */
    public function testSurface () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getSurface());

        // invalid
        $oHouse->setSurface(-1);
        static::assertEquals(0, $oHouse->getSurface());
        $oHouse->setSurface(1560);
        static::assertEquals(0, $oHouse->getSurface());

        // valid
        $iSurface = 50;
        $oHouse->setSurface($iSurface);
        static::assertEquals($iSurface, $oHouse->getSurface());
        $iSurface = 60;
        $oHouse->setSurface($iSurface);
        static::assertEquals($iSurface, $oHouse->getSurface());
        $iSurface = 100;
        $oHouse->setSurface($iSurface);
        static::assertEquals($iSurface, $oHouse->getSurface());
    }

    /**
     * Test house rent.
     */
    public function testRent () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getRent());

        // invalid
        $oHouse->setRent(-1);
        static::assertEquals(0, $oHouse->getRent());
        $oHouse->setRent(3000);
        static::assertEquals(0, $oHouse->getRent());

        // valid
        $fRent = 0;
        $oHouse->setRent($fRent);
        static::assertEquals($fRent, $oHouse->getRent());
        $fRent = 100;
        $oHouse->setRent($fRent);
        static::assertEquals($fRent, $oHouse->getRent());
        $fRent = 1000;
        $oHouse->setRent($fRent);
        static::assertEquals($fRent, $oHouse->getRent());
    }

    /**
     * Test house fees.
     */
    public function testFees () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getFees());

        // invalid
        $oHouse->setFees(-1);
        static::assertEquals(0, $oHouse->getFees());
        $oHouse->setFees(3000);
        static::assertEquals(0, $oHouse->getFees());

        // valid
        $fFees = 0;
        $oHouse->setFees($fFees);
        static::assertEquals($fFees, $oHouse->getFees());
        $fFees = 100;
        $oHouse->setFees($fFees);
        static::assertEquals($fFees, $oHouse->getFees());
        $fFees = 1000;
        $oHouse->setFees($fFees);
        static::assertEquals($fFees, $oHouse->getFees());
    }

    /**
     * Test house guarantee.
     */
    public function testGuarantee () {
        $oHouse = new House();

        // default value
        static::assertEquals(0, $oHouse->getGuarantee());

        // invalid
        $oHouse->setGuarantee(-1);
        static::assertEquals(0, $oHouse->getGuarantee());
        $oHouse->setGuarantee(3000);
        static::assertEquals(0, $oHouse->getGuarantee());

        // valid
        $fGuarantee = 0;
        $oHouse->setGuarantee($fGuarantee);
        static::assertEquals($fGuarantee, $oHouse->getGuarantee());
        $fGuarantee = 100;
        $oHouse->setGuarantee($fGuarantee);
        static::assertEquals($fGuarantee, $oHouse->getGuarantee());
        $fGuarantee = 1000;
        $oHouse->setGuarantee($fGuarantee);
        static::assertEquals($fGuarantee, $oHouse->getGuarantee());
    }

    /**
     * Test house has bath.
     */
    public function testHasBath () {
        $oHouse = new House();

        // default value
        static::assertFalse($oHouse->hasBath());

        // invalid
        $oHouse->setBath('');
        static::assertFalse($oHouse->hasBath());
        $oHouse->setBath(156);
        static::assertTrue($oHouse->hasBath());

        // valid
        $oHouse->setBath(false);
        static::assertFalse($oHouse->hasBath());
        $oHouse->setBath(true);
        static::assertTrue($oHouse->hasBath());
    }

    /**
     * Test house date.
     */
    public function testDate () {
        $oHouse = new House();

        // default value
        $oNow = new \DateTime("now");
        static::assertNotNull($oHouse->getDate());
        static::assertEquals($oNow->format(\DateTime::W3C), $oHouse->getDate()->format(\DateTime::W3C));

        // valid
        $oNow->modify("-1 YEAR");
        $oHouse->setDate($oNow);
        static::assertEquals($oNow->format(\DateTime::W3C), $oHouse->getDate()->format(\DateTime::W3C));
    }
}
