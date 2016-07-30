<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class AdminControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class AdminControllerTest extends \PHPUnit_Framework_TestCase {

    public function testCityPage () {
        $oController = new AdminController();
        $oController->setCurrentRoute('city');

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('cities', $aValidPageData);
        $this->assertContainsOnlyInstancesOf(City::class, $aValidPageData['cities']);
    }

    public function testDisplayPage () {
        $oController = new AdminController();
        $oController->setCurrentRoute('city');
        $sOutput = $oController->displayPage();

        $this->assertNotEmpty($sOutput);
    }
}
