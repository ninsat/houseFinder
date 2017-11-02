<?php

namespace YoannBlot\Framework\Controller;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;

/**
 * Class AbstractControllerTest
 *
 * @package YoannBlot\Framework\Controller
 * @author  Yoann Blot
 */
class AbstractControllerTest extends TestCase {

    const VALID_PAGE = 'valid';
    const INVALID_PAGE = 'fakeXC';

    /**
     * Test to get current page.
     */
    public function testGetCurrentPage () {
        $oController = new FakeController();
        static::assertNotEmpty($oController->getCurrent());
        static::assertEquals(AbstractController::DEFAULT_PAGE, $oController->getCurrent());

        // invalid page
        $oController->setCurrentRoute(static::INVALID_PAGE);
        static::assertEquals(AbstractController::DEFAULT_PAGE, $oController->getCurrent());

        // valid page
        $oController->setCurrentRoute(static::VALID_PAGE);
        static::assertEquals(static::VALID_PAGE, $oController->getCurrent());
    }

    public function testIsValidPage () {
        $oController = new FakeController();

        $oMethod = Reflection::getMethod($oController, "isRouteValid");

        static::assertFalse($oMethod->invoke($oController, static::INVALID_PAGE));
        static::assertTrue($oMethod->invoke($oController, static::VALID_PAGE));
    }

    public function testGetPage () {
        $oController = new FakeController();
        $oController->setCurrentRoute('valid');

        $aValidPageData = Reflection::getValue($oController, "getRouteData");
        static::assertNotEmpty($aValidPageData);
        static::assertArrayHasKey('title', $aValidPageData);
    }

    public function testMatchPath () {
        $oController = new FakeController();

        $oMethod = Reflection::getMethod($oController, "matchPath");

        static::assertFalse($oMethod->invoke($oController, 'wrong'));
        static::assertTrue($oMethod->invoke($oController, '/fake/'));
    }

    public function testMatchPathWithoutPath () {
        $oController = new NoPathController();

        $oMethod = Reflection::getMethod($oController, "matchPath");

        $_SERVER['REQUEST_URI'] = '';
        static::assertFalse($oMethod->invoke($oController, ''));

        $_SERVER['REQUEST_URI'] = 'wrong';
        static::assertFalse($oMethod->invoke($oController, 'wrong'));

        $_SERVER['REQUEST_URI'] = '/fake/';
        static::assertFalse($oMethod->invoke($oController, '/fake/'));
    }

}
