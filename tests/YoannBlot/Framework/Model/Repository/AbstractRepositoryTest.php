<?php

namespace YoannBlot\Framework\Model\Repository;

use YoannBlot\Framework\Model\Exception\QueryException;

/**
 * Class AbstractRepositoryTest
 *
 * @package YoannBlot\Framework\Model\Repository
 * @author  Yoann Blot
 */
class AbstractRepositoryTest extends \PHPUnit_Framework_TestCase {

    public function testGet () {
        $oRepository = new FakeRepository();

        $this->expectException(QueryException::class);
        $oRepository->get(0);
    }

    public function testGetAll () {
        $oRepository = new FakeRepository();
        $aObjects = $oRepository->getAll();
        $this->assertNotNull($aObjects);
        $this->assertCount(0, $aObjects);
    }
}
