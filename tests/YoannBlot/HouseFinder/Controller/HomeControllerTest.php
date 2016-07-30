<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class HomeControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class HomeControllerTest extends \PHPUnit_Framework_TestCase {

    public function testHomePage () {
        $oController = new HomeController();

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('cities', $aValidPageData);
        $this->assertContainsOnlyInstancesOf(City::class, $aValidPageData['cities']);
    }

    public function testDisplayPage () {
        $oController = new HomeController();
        $sOutput = $oController->displayPage();

        $this->assertNotEmpty($sOutput);
    }
}
