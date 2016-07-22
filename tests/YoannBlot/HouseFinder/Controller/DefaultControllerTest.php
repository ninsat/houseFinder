<?php

namespace YoannBlot\HouseFinder\Controller;

use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\HouseFinder\Model\Entity\City;

/**
 * Class DefaultControllerTest
 *
 * @package YoannBlot\HouseFinder\Controller
 * @author  Yoann Blot
 */
class DefaultControllerTest extends \PHPUnit_Framework_TestCase {

    public function testIndexPage () {
        $oController = new DefaultController();

        $aValidPageData = Reflection::getValue($oController, "getPageData");
        $this->assertNotEmpty($aValidPageData);
        $this->assertArrayHasKey('cities', $aValidPageData);
        $this->assertContainsOnlyInstancesOf(City::class, $aValidPageData['cities']);
    }

    public function testDisplayPage () {
        $oController = new DefaultController();
        $sOutput = $oController->displayPage();

        $this->assertNotEmpty($sOutput);
    }
}
