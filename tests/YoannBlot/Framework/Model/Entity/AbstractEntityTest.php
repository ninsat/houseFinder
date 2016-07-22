<?php

namespace YoannBlot\Framework\Model\Entity;

/**
 * Class AbstractEntityTest
 *
 * @package YoannBlot\Framework\Model\Entity
 * @author  Yoann Blot
 */
class AbstractEntityTest extends \PHPUnit_Framework_TestCase {

    public function testId () {
        $oEntity = new Fake();

        $this->assertNotNull($oEntity->getId());
        $this->assertEquals(AbstractEntity::DEFAULT_ID, $oEntity->getId());

        $iId = 1;
        $oEntity->setId($iId);

        $this->assertNotNull($oEntity->getId());
        $this->assertEquals($iId, $oEntity->getId());
    }
}
