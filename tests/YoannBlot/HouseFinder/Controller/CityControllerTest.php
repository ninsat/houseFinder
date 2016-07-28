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
        $oController = new CityController();
        $oController->setCurrentPage('index');

        $aValidPageData = Reflection::getValue($oController, "getPageData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('city', $aValidPageData);
        $this->assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testHousesPage () {
        $oController = new CityController();
        $oController->setCurrentPage('houses');

        $aValidPageData = Reflection::getValue($oController, "getPageData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('city', $aValidPageData);
        $this->assertInstanceOf(City::class, $aValidPageData['city']);
    }

    public function testDisplayPage () {
        $oController = new CityController();
        $oController->setCurrentPage('index');
        $sOutput = $oController->displayPage();

        $this->assertNotEmpty($sOutput);
    }
}
