<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\City;

/**
 * Class AdminControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 */
class AdminControllerTest extends \PHPUnit_Framework_TestCase {

    public function testCityPage () {
        $oController = new AdminController();
        $oController->setCurrentPage('city');

        $aValidPageData = Reflection::getValue($oController, "getPageData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('city', $aValidPageData);
        $this->assertInstanceOf(City::class, $aValidPageData['city']);
    }
}
