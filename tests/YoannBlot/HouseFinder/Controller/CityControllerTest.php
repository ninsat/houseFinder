<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class CityControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class CityControllerTest extends \PHPUnit_Framework_TestCase {

    public function testIndexPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('index', [$iCityId]);

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('city', $aValidPageData);
        $this->assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testHousesPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('houses', [$iCityId]);

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('city', $aValidPageData);
        $this->assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testDisplayPage () {
        $iCityId = 1;
        $oController = new CityController();
        $oController->setCurrentRoute('index', [$iCityId]);
        $sOutput = $oController->displayPage();

        $this->assertNotEmpty($sOutput);
    }
}
