<?php

namespace YoannBlot\HouseFinder\Controller;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class HomeControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class HomeControllerTest extends TestCase {

    public function testHomePage () {
        $oController = new HomeController();

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        static::assertNotEmpty($aValidPageData);
        static::assertArrayHasKey('cities', $aValidPageData);
        static::assertContainsOnlyInstancesOf(City::class, $aValidPageData['cities']);
    }

    public function testDisplayPage () {
        $oController = new HomeController();
        $sOutput = $oController->displayPage();

        static::assertNotEmpty($sOutput);
    }
}
