<?php
declare(strict_types=1);

namespace YoannBlot\Framework\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * Class AbstractEntityTest
 *
 * @package YoannBlot\Framework\Model\Entity
 * @author  Yoann Blot
 */
class AbstractEntityTest extends TestCase {

    public function testId () {
        $oEntity = new Fake();

        static::assertNotNull($oEntity->getId());
        static::assertEquals(AbstractEntity::DEFAULT_ID, $oEntity->getId());

        $iId = 1;
        $oEntity->setId($iId);

        static::assertNotNull($oEntity->getId());
        static::assertEquals($iId, $oEntity->getId());
    }
}
