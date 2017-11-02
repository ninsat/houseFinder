<?php

namespace Framework\Model\DataBase;

use PHPUnit\Framework\TestCase;
use YoannBlot\Framework\Helper\Reflection;
use YoannBlot\Framework\Model\DataBase\Connector;
use YoannBlot\Framework\Model\Entity\Fake;
use YoannBlot\Framework\Model\Exception\EntityNotFoundException;
use YoannBlot\Framework\Model\Exception\QueryException;
use YoannBlot\HouseFinder\Model\Repository\CityRepository;

/**
 * Class ConnectorTest
 * @package Framework\Model\DataBase
 */
class ConnectorTest extends TestCase {

    /**
     * @var Connector
     */
    private $oConnector;

    protected function setUp () {
        $this->oConnector = Connector::get();
        Reflection::getValue($this->oConnector, 'initConnection');
        static::assertTrue(Reflection::getValue($this->oConnector, 'isConnected'));
    }

    public function testClose () {
        $this->oConnector->close();
        static::assertFalse(Reflection::getValue($this->oConnector, 'isConnected'));
    }

    public function testDestructor () {
        $this->oConnector->__destruct();
        static::assertFalse(Reflection::getValue($this->oConnector, 'isConnected'));
    }

    public function testQuerySingleFail () {
        $sFakeQuery = 'selct * from glou';

        $this->expectException(QueryException::class);
        $this->oConnector->querySingle($sFakeQuery, Fake::class);
    }

    public function testQuerySingleEntityNotFound () {
        $oRepository = new CityRepository();
        $sFakeQuery = 'select * from ' . $oRepository->getTable() . ' where id = -1 limit 1';

        $this->expectException(EntityNotFoundException::class);
        $this->oConnector->querySingle($sFakeQuery, Fake::class);
    }
}
