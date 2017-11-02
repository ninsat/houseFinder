<?php

namespace YoannBlot\HouseFinder\Controller;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class AdminControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class AdminControllerTest extends TestCase {

    public function testCityPage () {
        $oController = new AdminController();
        $oController->setCurrentRoute('city');

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        static::assertNotEmpty($aValidPageData);
        static::assertArrayHasKey('cities', $aValidPageData);
        static::assertContainsOnlyInstancesOf(City::class, $aValidPageData['cities']);
    }

    public function testDisplayPage () {
        $oController = new AdminController();
        $oController->setCurrentRoute('city');
        $sOutput = $oController->displayPage();

        static::assertNotEmpty($sOutput);
    }
}
