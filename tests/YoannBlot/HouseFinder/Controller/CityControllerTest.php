<?php

namespace YoannBlot\HouseFinder\Controller;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class CityControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class CityControllerTest extends TestCase {

    public function testIndexPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('index', [$iCityId]);

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        static::assertNotEmpty($aValidPageData);
        static::assertArrayHasKey('city', $aValidPageData);
        static::assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testHousesPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('houses', [$iCityId]);

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        static::assertNotEmpty($aValidPageData);
        static::assertArrayHasKey('city', $aValidPageData);
        static::assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testDisplayPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('index', [$iCityId]);
        $sOutput = $oController->displayPage();

        static::assertNotEmpty($sOutput);
    }
}
