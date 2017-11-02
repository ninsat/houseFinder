<?php

namespace YoannBlot\Framework\Model\Repository;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Model\Exception\QueryException;

/**
 * Class AbstractRepositoryTest
 *
 * @package YoannBlot\Framework\Model\Repository
 * @author  Yoann Blot
 */
class AbstractRepositoryTest extends TestCase {

    public function testGet () {
        $oRepository = new FakeRepository();

        $this->expectException(QueryException::class);
        $oRepository->get(0);
    }

    public function testGetAll () {
        $oRepository = new FakeRepository();
        $aObjects = $oRepository->getAll();
        static::assertNotNull($aObjects);
        static::assertCount(0, $aObjects);
    }
}
